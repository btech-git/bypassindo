<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\PurchaseInvoiceHeader;
use AppBundle\Repository\Transaction\PurchaseInvoiceHeaderRepository;

class PurchaseInvoiceHeaderForm
{
    private $purchaseInvoiceHeaderRepository;
    
    public function __construct(PurchaseInvoiceHeaderRepository $purchaseInvoiceHeaderRepository)
    {
        $this->purchaseInvoiceHeaderRepository = $purchaseInvoiceHeaderRepository;
    }
    
    public function initialize(PurchaseInvoiceHeader $purchaseInvoiceHeader, array $params = array())
    {
        list($staff) = array($params['staff']);
        
        if (empty($purchaseInvoiceHeader->getId())) {
            $purchaseInvoiceHeader->setStaffFirst($staff);
        }
        $purchaseInvoiceHeader->setStaffLast($staff);
    }
    
    public function finalize(PurchaseInvoiceHeader $purchaseInvoiceHeader, array $params = array())
    {
        if (empty($purchaseInvoiceHeader->getId())) {
            $transactionDate = $purchaseInvoiceHeader->getTransactionDate();
            if ($transactionDate !== null) {
                $month = intval($transactionDate->format('m'));
                $year = intval($transactionDate->format('y'));
                $lastPurchaseInvoiceHeaderApplication = $this->purchaseInvoiceHeaderRepository->findRecentBy($year, $month);
                $currentPurchaseInvoiceHeader = ($lastPurchaseInvoiceHeaderApplication === null) ? $purchaseInvoiceHeader : $lastPurchaseInvoiceHeaderApplication;
                $purchaseInvoiceHeader->setCodeNumberToNext($currentPurchaseInvoiceHeader->getCodeNumber(), $year, $month);
            }
        }
        foreach ($purchaseInvoiceHeader->getPurchaseInvoiceDetails() as $purchaseInvoiceDetail) {
            $purchaseInvoiceDetail->setPurchaseInvoiceHeader($purchaseInvoiceHeader);
        }
        $this->sync($purchaseInvoiceHeader);
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
                'purchaseInvoiceDetails' => array('add' => true),
            ));
        } else {
            $this->purchaseInvoiceHeaderRepository->update($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $this->beforeDelete($purchaseInvoiceHeader);
        if (!empty($purchaseInvoiceHeader->getId())) {
            $this->purchaseInvoiceHeaderRepository->remove($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $purchaseInvoiceHeader->getPurchaseInvoiceDetails()->clear();
        $this->sync($purchaseInvoiceHeader);
    }
}
