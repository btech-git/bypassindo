<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\PurchaseInvoiceHeader;
use AppBundle\Entity\Transaction\PurchaseInvoiceDetailWorkshop;
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
        $this->sync($purchaseInvoiceHeader);
    }
    
    private function sync(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $receiveWorkshop = $purchaseInvoiceHeader->getReceiveWorkshop();
        if ($receiveWorkshop !== null) {
            $purchaseWorkshopHeader = $receiveWorkshop->getDeliveryWorkshop()->getPurchaseWorkshopHeader();
            $purchaseInvoiceHeader->setSupplier($purchaseWorkshopHeader->getSupplier());
            $purchaseInvoiceHeader->setIsTax($purchaseWorkshopHeader->getIsTax());
            $purchaseInvoiceDetailWorkshops = $purchaseInvoiceHeader->getPurchaseInvoiceDetailWorkshops();
            $purchaseInvoiceDetailWorkshops->clear();
            foreach ($purchaseWorkshopHeader->getPurchaseWorkshopDetails() as $purchaseWorkshopDetail) {
                $purchaseInvoiceDetailWorkshop = new PurchaseInvoiceDetailWorkshop();
                $purchaseInvoiceDetailWorkshop->setItemName($purchaseWorkshopDetail->getItemName());
                $purchaseInvoiceDetailWorkshop->setQuantity($purchaseWorkshopDetail->getQuantity());
                $purchaseInvoiceDetailWorkshop->setUnitPrice($purchaseWorkshopDetail->getUnitPrice());
                $purchaseInvoiceDetailWorkshop->setTotal($purchaseWorkshopDetail->getTotal());
                $purchaseInvoiceDetailWorkshop->setPurchaseInvoiceHeader($purchaseInvoiceHeader);
                $purchaseInvoiceDetailWorkshops->add($purchaseInvoiceDetailWorkshop);
            }
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
