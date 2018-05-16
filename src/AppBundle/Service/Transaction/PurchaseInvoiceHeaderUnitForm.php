<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\PurchaseInvoiceHeader;
use AppBundle\Repository\Transaction\PurchaseInvoiceHeaderRepository;

class PurchaseInvoiceHeaderUnitForm
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
            
            $purchaseInvoiceHeader->setReceiveWorkshop(null);
            $purchaseInvoiceHeader->setBusinessType(PurchaseInvoiceHeader::BUSINESS_TYPE_UNIT);
            $purchaseInvoiceHeader->setStaffFirst($staff);
        }
        $purchaseInvoiceHeader->setStaffLast($staff);
    }
    
    public function finalize(PurchaseInvoiceHeader $purchaseInvoiceHeader, array $params = array())
    {
        foreach ($purchaseInvoiceHeader->getPurchaseInvoiceDetailUnits() as $purchaseInvoiceDetailUnit) {
            $purchaseInvoiceDetailUnit->setPurchaseInvoiceHeader($purchaseInvoiceHeader);
        }
        $this->sync($purchaseInvoiceHeader);
    }
    
    private function sync(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        foreach ($purchaseInvoiceHeader->getPurchaseInvoiceDetailUnits() as $purchaseInvoiceDetailUnit) {
            $purchaseInvoiceDetailUnit->setVehicleChassisNumber($purchaseInvoiceDetailUnit->getPurchaseDeliveryOrder()->getVehicleChassisNumber());
            $purchaseInvoiceDetailUnit->setVehicleMachineNumber($purchaseInvoiceDetailUnit->getPurchaseDeliveryOrder()->getVehicleMachineNumber());
            $purchaseInvoiceDetailUnit->setQuantity('1');
        }
        $purchaseInvoiceHeader->sync();
    }
    
    public function save(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        if (empty($purchaseInvoiceHeader->getId())) {
            $this->purchaseInvoiceHeaderRepository->add($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetailUnits' => array('add' => true),
            ));
        } else {
            $this->purchaseInvoiceHeaderRepository->update($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetailUnits' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $this->beforeDelete($purchaseInvoiceHeader);
        if (!empty($purchaseInvoiceHeader->getId())) {
            $this->purchaseInvoiceHeaderRepository->remove($purchaseInvoiceHeader, array(
                'purchaseInvoiceDetailUnits' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(PurchaseInvoiceHeader $purchaseInvoiceHeader)
    {
        $purchaseInvoiceHeader->getPurchaseInvoiceDetailUnits()->clear();
        $this->sync($purchaseInvoiceHeader);
    }
}
