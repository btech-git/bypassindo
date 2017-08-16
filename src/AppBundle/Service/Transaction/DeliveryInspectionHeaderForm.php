<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\DeliveryInspectionHeader;
use AppBundle\Repository\Transaction\DeliveryInspectionHeaderRepository;

class DeliveryInspectionHeaderForm
{
    private $deliveryInspectionHeaderRepository;
    
    public function __construct(DeliveryInspectionHeaderRepository $deliveryInspectionHeaderRepository)
    {
        $this->deliveryInspectionHeaderRepository = $deliveryInspectionHeaderRepository;
    }
    
    public function initialize(DeliveryInspectionHeader $deliveryInspectionHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($deliveryInspectionHeader->getId())) {
            $lastDeliveryInspectionHeader = $this->deliveryInspectionHeaderRepository->findRecentBy($year, $month);
            $currentDeliveryInspectionHeader = ($lastDeliveryInspectionHeader === null) ? $deliveryInspectionHeader : $lastDeliveryInspectionHeader;
            $deliveryInspectionHeader->setCodeNumberToNext($currentDeliveryInspectionHeader->getCodeNumber(), $year, $month);
            
            $deliveryInspectionHeader->setStaffFirst($staff);
        }
        $deliveryInspectionHeader->setStaffLast($staff);
    }
    
    public function finalize(DeliveryInspectionHeader $deliveryInspectionHeader, array $params = array())
    {
        foreach ($deliveryInspectionHeader->getDeliveryInspectionDetails() as $deliveryInspectionDetails) {
            $deliveryInspectionDetails->setDeliveryInspectionHeader($deliveryInspectionHeader);
        }
        $this->sync($deliveryInspectionHeader);
    }
    
    private function sync(DeliveryInspectionHeader $deliveryInspectionHeader)
    {
//        $total = $deliveryInspectionHeader->getQuantity() * $deliveryInspectionHeader->getUnitPrice();
//        $deliveryInspectionHeader->setTotal($total);
    }
    
    public function save(DeliveryInspectionHeader $deliveryInspectionHeader)
    {
        if (empty($deliveryInspectionHeader->getId())) {
            $this->deliveryInspectionHeaderRepository->add($deliveryInspectionHeader, array(
                'deliveryInspectionDetails' => array('add' => true),
            ));
        } else {
            $this->deliveryInspectionHeaderRepository->update($deliveryInspectionHeader, array(
                'deliveryInspectionDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(DeliveryInspectionHeader $deliveryInspectionHeader)
    {
        $this->beforeDelete($deliveryInspectionHeader);
        if (!empty($deliveryInspectionHeader->getId())) {
            $this->deliveryInspectionHeaderRepository->remove($deliveryInspectionHeader, array(
                'deliveryInspectionDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(DeliveryInspectionHeader $deliveryInspectionHeader)
    {
        $deliveryInspectionHeader->getDeliveryInspectionDetails()->clear();
        $this->sync($deliveryInspectionHeader);
    }
}
