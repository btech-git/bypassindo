<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\ReceiveOrder;
use AppBundle\Repository\Transaction\ReceiveOrderRepository;

class ReceiveOrderForm
{
    private $receiveOrderRepository;
    
    public function __construct(ReceiveOrderRepository $receiveOrderRepository)
    {
        $this->receiveOrderRepository = $receiveOrderRepository;
    }
    
    public function initialize(ReceiveOrder $receiveOrder, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($receiveOrder->getId())) {
            $lastReceiveOrder = $this->receiveOrderRepository->findRecentBy($year, $month);
            $currentReceiveOrder = ($lastReceiveOrder === null) ? $receiveOrder : $lastReceiveOrder;
            $receiveOrder->setCodeNumberToNext($currentReceiveOrder->getCodeNumber(), $year, $month);
            
            $receiveOrder->setStaffFirst($staff);
        }
        $receiveOrder->setStaffLast($staff);
    }
    
    public function finalize(ReceiveOrder $receiveOrder, array $params = array())
    {
        $this->sync($receiveOrder);
    }
    
    private function sync(ReceiveOrder $receiveOrder)
    {
    }
    
    public function save(ReceiveOrder $receiveOrder)
    {
        if (empty($receiveOrder->getId())) {
            $this->receiveOrderRepository->add($receiveOrder);
        } else {
            $this->receiveOrderRepository->update($receiveOrder);
        }
    }
    
    public function delete(ReceiveOrder $receiveOrder)
    {
        $this->beforeDelete($receiveOrder);
        if (!empty($receiveOrder->getId())) {
            $this->receiveOrderRepository->remove($receiveOrder);
        }
    }
    
    protected function beforeDelete(ReceiveOrder $receiveOrder)
    {
        $this->sync($receiveOrder);
    }
}
