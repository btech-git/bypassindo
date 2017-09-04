<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\DeliveryOrder;
use AppBundle\Repository\Transaction\DeliveryOrderRepository;

class DeliveryOrderForm
{
    private $deliveryOrderRepository;
    
    public function __construct(DeliveryOrderRepository $deliveryOrderRepository)
    {
        $this->deliveryOrderRepository = $deliveryOrderRepository;
    }
    
    public function initialize(DeliveryOrder $deliveryOrder, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($deliveryOrder->getId())) {
            $lastDeliveryOrder = $this->deliveryOrderRepository->findRecentBy($year, $month);
            $currentDeliveryOrder = ($lastDeliveryOrder === null) ? $deliveryOrder : $lastDeliveryOrder;
            $deliveryOrder->setCodeNumberToNext($currentDeliveryOrder->getCodeNumber(), $year, $month);
            
            $deliveryOrder->setStaffFirst($staff);
        }
        $deliveryOrder->setStaffLast($staff);
    }
    
    public function finalize(DeliveryOrder $deliveryOrder, array $params = array())
    {
        $this->sync($deliveryOrder);
    }
    
    private function sync(DeliveryOrder $deliveryOrder)
    {
    }
    
    public function save(DeliveryOrder $deliveryOrder)
    {
        if (empty($deliveryOrder->getId())) {
            $this->deliveryOrderRepository->add($deliveryOrder);
        } else {
            $this->deliveryOrderRepository->update($deliveryOrder);
        }
    }
    
    public function delete(DeliveryOrder $deliveryOrder)
    {
        $this->beforeDelete($deliveryOrder);
        if (!empty($deliveryOrder->getId())) {
            $this->deliveryOrderRepository->remove($deliveryOrder);
        }
    }
    
    protected function beforeDelete(DeliveryOrder $deliveryOrder)
    {
        $this->sync($deliveryOrder);
    }
}
