<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\PurchaseInvoiceHeader;
use AppBundle\Repository\Transaction\PurchaseInvoiceHeaderRepository;

class PurchaseInvoiceHeaderWorkshopForm
{
    private $purchaseInvoiceHeaderRepository;
    
    public function __construct(PurchaseInvoiceHeaderRepository $purchaseInvoiceHeaderRepository)
    {
        $this->purchaseInvoiceHeaderRepository = $purchaseInvoiceHeaderRepository;
    }
    
    public function initialize(PurchaseInvoiceHeader $purchaseInvoiceHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($purchaseInvoiceHeader->getId())) {
            $lastPurchaseInvoiceHeader = $this->purchaseInvoiceHeaderRepository->findRecentBy($year, $month);
            $currentPurchaseInvoiceHeader = ($lastPurchaseInvoiceHeader === null) ? $purchaseInvoiceHeader : $lastPurchaseInvoiceHeader;
            $purchaseInvoiceHeader->setCodeNumberToNext($currentPurchaseInvoiceHeader->getCodeNumber(), $year, $month);
            
            $purchaseInvoiceHeader->setBusinessType(PurchaseInvoiceHeader::BUSINESS_TYPE_WORKSHOP);
            $purchaseInvoiceHeader->setStaffFirst($staff);
        }
        $purchaseInvoiceHeader->setStaffLast($staff);
    }
    
    public function finalize(PurchaseInvoiceHeader $purchaseInvoiceHeader, array $params = array())
    {
        foreach ($purchaseInvoiceHeader->getPurchaseInvoiceDetailWorkshops() as $purchaseInvoiceDetailWorkshop) {
            $purchaseInvoiceDetailWorkshop->setPurchaseInvoiceHeader($purchaseInvoiceHeader);
        }
        $purchaseWorkshopHeader = $purchaseInvoiceHeader->getPurchaseWorkshopHeader();
        if ($purchaseWorkshopHeader !== null) {
            $purchaseInvoiceHeader->setSupplier($purchaseWorkshopHeader->getSupplier());
            $purchaseInvoiceHeader->setIsTax($purchaseWorkshopHeader->getIsTax());
            $purchaseInvoiceDetailWorkshops = $purchaseInvoiceHeader->getPurchaseInvoiceDetailWorkshops();
            $purchaseInvoiceDetailWorkshops->clear();
            foreach ($purchaseWorkshopHeader->getPurchaseWorkshopDetails() as $purchaseWorkshopDetail) {
                $purchaseInvoiceDetailWorkshop = new \AppBundle\Entity\Transaction\PurchaseInvoiceDetailWorkshop();
                $purchaseInvoiceDetailWorkshop->setItemName($purchaseWorkshopDetail->getItemName());
                $purchaseInvoiceDetailWorkshop->setQuantity($purchaseWorkshopDetail->getQuantity());
                $purchaseInvoiceDetailWorkshop->setUnitPrice($purchaseWorkshopDetail->getUnitPrice());
                $purchaseInvoiceDetailWorkshop->setTotal($purchaseWorkshopDetail->getTotal());
                $purchaseInvoiceDetailWorkshop->setPurchaseInvoiceHeader($purchaseInvoiceHeader);
                $purchaseInvoiceDetailWorkshops->add($purchaseInvoiceDetailWorkshop);
            }
        }
        $purchaseInvoiceHeader->sync();
//        $this->sync($purchaseInvoiceHeader);
    }
    
    private function sync(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $subTotal = 0.00;
        foreach ($purchaseInvoiceHeader->getPurchaseInvoiceDetails() as $purchaseInvoiceDetail) {
            $total = $purchaseInvoiceDetail->getQuantity() * $purchaseInvoiceDetail->getUnitPrice();
            $purchaseInvoiceDetail->setTotal($total);
            $subTotal += $total;
        }
        $purchaseInvoiceHeader->setSubTotal($subTotal);
        $taxNominal = $purchaseInvoiceHeader->getIsTax() ? $subTotal * 0.1 : 0;
        $purchaseInvoiceHeader->setTaxNominal($taxNominal);
        $grandTotal = $subTotal + $taxNominal;
        $purchaseInvoiceHeader->setGrandTotal($grandTotal);
        
        $purchaseWorkshopHeader = $purchaseInvoiceHeader->getPurchaseWorkshopHeader();
        if ($purchaseInvoiceHeader->getIsPurchaseWorkshopHeader() && $purchaseWorkshopHeader !== null) {
            $purchaseInvoiceHeader->setSupplier($purchaseWorkshopHeader->getSupplier());
            $purchaseInvoiceDetails = $purchaseInvoiceHeader->getPurchaseInvoiceDetails();
            $purchaseInvoiceDetails->clear();
            foreach ($purchaseWorkshopHeader->getPurchaseWorkshopDetails() as $purchaseWorkshopDetail) {
                $purchaseInvoiceDetail = new \AppBundle\Entity\Transaction\PurchaseInvoiceDetail();
                $purchaseInvoiceDetail->setItemName($purchaseWorkshopDetail->getItemName());
                $purchaseInvoiceDetail->setQuantity($purchaseWorkshopDetail->getQuantity());
                $purchaseInvoiceDetail->setUnitPrice($purchaseWorkshopDetail->getUnitPrice());
                $purchaseInvoiceDetail->setTotal($purchaseWorkshopDetail->getTotal());
                $purchaseInvoiceDetail->setPurchaseInvoiceHeader($purchaseInvoiceHeader);
                $purchaseInvoiceDetails->add($purchaseInvoiceDetail);
            }
        } else {
            $purchaseInvoiceHeader->setPurchaseWorkshopHeader(null);
        }
        $purchaseInvoiceHeader->sync();
    }
    
    public function save(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        if (empty($purchaseInvoiceHeader->getId())) {
            $this->purchaseInvoiceHeaderRepository->add($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetailWorkshops' => array('add' => true),
            ));
        } else {
            $this->purchaseInvoiceHeaderRepository->update($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetailWorkshops' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $this->beforeDelete($purchaseInvoiceHeader);
        if (!empty($purchaseInvoiceHeader->getId())) {
            $this->purchaseInvoiceHeaderRepository->remove($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetailWorkshops' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $purchaseInvoiceHeader->getPurchaseInvoiceDetailWorkshops()->clear();
        $this->sync($purchaseInvoiceHeader);
    }
}
