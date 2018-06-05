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
        list($staff) = array($params['staff']);
        
        if (empty($deliveryWorkshop->getId())) {
            $deliveryWorkshop->setStaffFirst($staff);
        }
        $deliveryWorkshop->setStaffLast($staff);
    }
    
    public function finalize(DeliveryWorkshop $deliveryWorkshop, array $params = array())
    {
        if (empty($deliveryWorkshop->getId())) {
            $transactionDate = $deliveryWorkshop->getTransactionDate();
            if ($transactionDate !== null) {
                $month = intval($transactionDate->format('m'));
                $year = intval($transactionDate->format('y'));
                $lastDeliveryWorkshopApplication = $this->deliveryWorkshopRepository->findRecentBy($year, $month);
                $currentDeliveryWorkshop = ($lastDeliveryWorkshopApplication === null) ? $deliveryWorkshop : $lastDeliveryWorkshopApplication;
                $deliveryWorkshop->setCodeNumberToNext($currentDeliveryWorkshop->getCodeNumber(), $year, $month);
            }
        }
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
