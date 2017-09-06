<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\PurchaseWorkshopHeader;
use AppBundle\Repository\Transaction\PurchaseWorkshopHeaderRepository;

class PurchaseWorkshopHeaderForm
{
    private $purchaseWorkshopHeaderRepository;
    
    public function __construct(PurchaseWorkshopHeaderRepository $purchaseWorkshopHeaderRepository)
    {
        $this->purchaseWorkshopHeaderRepository = $purchaseWorkshopHeaderRepository;
    }
    
    public function initialize(PurchaseWorkshopHeader $purchaseWorkshopHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($purchaseWorkshopHeader->getId())) {
            $lastPurchaseWorkshopHeader = $this->purchaseWorkshopHeaderRepository->findRecentBy($year, $month);
            $currentPurchaseWorkshopHeader = ($lastPurchaseWorkshopHeader === null) ? $purchaseWorkshopHeader : $lastPurchaseWorkshopHeader;
            $purchaseWorkshopHeader->setCodeNumberToNext($currentPurchaseWorkshopHeader->getCodeNumber(), $year, $month);
            
            $purchaseWorkshopHeader->setStaffFirst($staff);
        }
        $purchaseWorkshopHeader->setStaffLast($staff);
    }
    
    public function finalize(PurchaseWorkshopHeader $purchaseWorkshopHeader, array $params = array())
    {
        foreach ($purchaseWorkshopHeader->getPurchaseWorkshopDetails() as $purchaseWorkshopDetail) {
            $purchaseWorkshopDetail->setPurchaseWorkshopHeader($purchaseWorkshopHeader);
        }
        $this->sync($purchaseWorkshopHeader);
    }
    
    private function sync(PurchaseWorkshopHeader $purchaseWorkshopHeader)
    {
        $grandTotal = 0.00;
        foreach ($purchaseWorkshopHeader->getPurchaseWorkshopDetails() as $purchaseWorkshopDetail) {
            $total = $purchaseWorkshopDetail->getQuantity() * $purchaseWorkshopDetail->getUnitPrice();
            $purchaseWorkshopDetail->setTotal($total);
            $grandTotal += $total;
        }
        $purchaseWorkshopHeader->setGrandTotal($grandTotal);
    }
    
    public function save(PurchaseWorkshopHeader $purchaseWorkshopHeader)
    {
        if (empty($purchaseWorkshopHeader->getId())) {
            $this->purchaseWorkshopHeaderRepository->add($purchaseWorkshopHeader, array(
                'purchaseWorkshopDetails' => array('add' => true),
            ));
        } else {
            $this->purchaseWorkshopHeaderRepository->update($purchaseWorkshopHeader, array(
                'purchaseWorkshopDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(PurchaseWorkshopHeader $purchaseWorkshopHeader)
    {
        $this->beforeDelete($purchaseWorkshopHeader);
        if (!empty($purchaseWorkshopHeader->getId())) {
            $this->purchaseWorkshopHeaderRepository->remove($purchaseWorkshopHeader, array(
                'purchaseWorkshopDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(PurchaseWorkshopHeader $purchaseWorkshopHeader)
    {
        $purchaseWorkshopHeader->getPurchaseWorkshopDetails()->clear();
        $this->sync($purchaseWorkshopHeader);
    }
}
