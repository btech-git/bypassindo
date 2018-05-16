<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SaleInvoiceHeader;
use AppBundle\Repository\Transaction\SaleInvoiceHeaderRepository;

class SaleInvoiceHeaderForm
{
    private $saleInvoiceHeaderRepository;
    
    public function __construct(SaleInvoiceHeaderRepository $saleInvoiceHeaderRepository)
    {
        $this->saleInvoiceHeaderRepository = $saleInvoiceHeaderRepository;
    }
    
    public function initialize(SaleInvoiceHeader $saleInvoiceHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($saleInvoiceHeader->getId())) {
            $lastSaleInvoice = $this->saleInvoiceHeaderRepository->findRecentBy($year, $month);
            $currentSaleInvoice = ($lastSaleInvoice === null) ? $saleInvoiceHeader : $lastSaleInvoice;
            $saleInvoiceHeader->setCodeNumberToNext($currentSaleInvoice->getCodeNumber(), $year, $month);
            
            $saleInvoiceHeader->setStaffFirst($staff);
        }
        $saleInvoiceHeader->setStaffLast($staff);
    }
    
    public function finalize(SaleInvoiceHeader $saleInvoiceHeader, array $params = array())
    {
        foreach ($saleInvoiceHeader->getSaleInvoiceDetails() as $saleInvoiceDetail) {
            $saleInvoiceDetail->setSaleInvoiceHeader($saleInvoiceHeader);
        }
        $this->sync($saleInvoiceHeader);
    }
    
    private function sync(SaleInvoiceHeader $saleInvoiceHeader)
    {
        foreach ($saleInvoiceHeader->getSaleInvoiceDetails() as $saleInvoiceDetail) {
            $saleInvoiceDetail->setVehicleChassisNumber($saleInvoiceDetail->getReceiveOrder()->getPurchaseDeliveryOrder()->getVehicleChassisNumber());
            $saleInvoiceDetail->setVehicleMachineNumber($saleInvoiceDetail->getReceiveOrder()->getPurchaseDeliveryOrder()->getVehicleMachineNumber());
            $saleInvoiceDetail->setQuantity('1');
            $saleInvoiceDetail->setUnitPrice($saleInvoiceDetail->getReceiveOrder()->getPurchaseDeliveryOrder()->getSaleOrder()->getTotal());
        }
        $saleInvoiceHeader->sync();
    }
    
    public function save(SaleInvoiceHeader $saleInvoiceHeader)
    {
        if (empty($saleInvoiceHeader->getId())) {
            $this->saleInvoiceHeaderRepository->add($saleInvoiceHeader, array(
                'saleInvoiceDetails' => array('add' => true),
            ));
        } else {
            $this->saleInvoiceHeaderRepository->update($saleInvoiceHeader, array(
                'saleInvoiceDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(SaleInvoiceHeader $saleInvoiceHeader)
    {
        $this->beforeDelete($saleInvoiceHeader);
        if (!empty($saleInvoiceHeader->getId())) {
            $this->saleInvoiceHeaderRepository->remove($saleInvoiceHeader, array(
                'saleInvoiceDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(SaleInvoiceHeader $saleInvoiceHeader)
    {
        $saleInvoiceHeader->getSaleInvoiceDetails()->clear();
        $this->sync($saleInvoiceHeader);
    }
}
