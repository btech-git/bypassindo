<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\DeliveryWorkshop;
use AppBundle\Repository\Transaction\DeliveryWorkshopRepository;

class DeliveryWorkshopForm
{
    private $deliveryWorkshopRepository;
    
    public function __construct(DeliveryWorkshopRepository $deliveryWorkshopRepository)
    {
        $this->deliveryWorkshopRepository = $deliveryWorkshopRepository;
    }
    
    public function initialize(DeliveryWorkshop $deliveryWorkshop, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($deliveryWorkshop->getId())) {
            $lastDeliveryWorkshop = $this->deliveryWorkshopRepository->findRecentBy($year, $month);
            $currentDeliveryWorkshop = ($lastDeliveryWorkshop === null) ? $deliveryWorkshop : $lastDeliveryWorkshop;
            $deliveryWorkshop->setCodeNumberToNext($currentDeliveryWorkshop->getCodeNumber(), $year, $month);
            
            $deliveryWorkshop->setStaffFirst($staff);
        }
        $deliveryWorkshop->setStaffLast($staff);
    }
    
    public function finalize(DeliveryWorkshop $deliveryWorkshop, array $params = array())
    {
        $this->sync($deliveryWorkshop);
    }
    
    private function sync(DeliveryWorkshop $deliveryWorkshop)
    {
    }
    
    public function save(DeliveryWorkshop $deliveryWorkshop)
    {
        if (empty($deliveryWorkshop->getId())) {
            $this->deliveryWorkshopRepository->add($deliveryWorkshop);
        } else {
            $this->deliveryWorkshopRepository->update($deliveryWorkshop);
        }
    }
    
    public function delete(DeliveryWorkshop $deliveryWorkshop)
    {
        $this->beforeDelete($deliveryWorkshop);
        if (!empty($deliveryWorkshop->getId())) {
            $this->deliveryWorkshopRepository->remove($deliveryWorkshop);
        }
    }
    
    protected function beforeDelete(DeliveryWorkshop $deliveryWorkshop)
    {
        $this->sync($deliveryWorkshop);
    }
}
