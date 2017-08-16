<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SaleOrder;
use AppBundle\Repository\Transaction\SaleOrderRepository;

class SaleOrderForm
{
    private $saleOrderRepository;
    
    public function __construct(SaleOrderRepository $saleOrderRepository)
    {
        $this->saleOrderRepository = $saleOrderRepository;
    }
    
    public function initialize(SaleOrder $saleOrder, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($saleOrder->getId())) {
            $lastSaleOrder = $this->saleOrderRepository->findRecentBy($year, $month);
            $currentSaleOrder = ($lastSaleOrder === null) ? $saleOrder : $lastSaleOrder;
            $saleOrder->setCodeNumberToNext($currentSaleOrder->getCodeNumber(), $year, $month);
            
            $saleOrder->setStaffFirst($staff);
        }
        $saleOrder->setStaffLast($staff);
    }
    
    public function finalize(SaleOrder $saleOrder, array $params = array())
    {
        $this->sync($saleOrder);
    }
    
    private function sync(SaleOrder $saleOrder)
    {
        $total = $saleOrder->getQuantity() * $saleOrder->getUnitPrice();
        $saleOrder->setTotal($total);
    }
    
    public function save(SaleOrder $saleOrder)
    {
        if (empty($saleOrder->getId())) {
            $this->saleOrderRepository->add($saleOrder);
        } else {
            $this->saleOrderRepository->update($saleOrder);
        }
    }
    
    public function delete(SaleOrder $saleOrder)
    {
        $this->beforeDelete($saleOrder);
        if (!empty($saleOrder->getId())) {
            $this->saleOrderRepository->remove($saleOrder);
        }
    }
    
    protected function beforeDelete(SaleOrder $saleOrder)
    {
        $this->sync($saleOrder);
    }
}
