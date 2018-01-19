<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\PurchaseDeliveryOrder;
use AppBundle\Repository\Transaction\PurchaseDeliveryOrderRepository;

class PurchaseDeliveryOrderForm
{
    private $purchaseDeliveryOrderRepository;
    
    public function __construct(PurchaseDeliveryOrderRepository $purchaseDeliveryOrderRepository)
    {
        $this->purchaseDeliveryOrderRepository = $purchaseDeliveryOrderRepository;
    }
    
    public function initialize(PurchaseDeliveryOrder $purchaseDeliveryOrder, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($purchaseDeliveryOrder->getId())) {
            $lastPurchaseDeliveryOrder = $this->purchaseDeliveryOrderRepository->findRecentBy($year, $month);
            $currentPurchaseDeliveryOrder = ($lastPurchaseDeliveryOrder === null) ? $purchaseDeliveryOrder : $lastPurchaseDeliveryOrder;
            $purchaseDeliveryOrder->setCodeNumberToNext($currentPurchaseDeliveryOrder->getCodeNumber(), $year, $month);
            
            $purchaseDeliveryOrder->setStaffFirst($staff);
        }
        $purchaseDeliveryOrder->setStaffLast($staff);
    }
    
    public function finalize(PurchaseDeliveryOrder $purchaseDeliveryOrder, array $params = array())
    {
        $this->sync($purchaseDeliveryOrder);
    }
    
    private function sync(PurchaseDeliveryOrder $purchaseDeliveryOrder)
    {
        $purchaseDeliveryOrder->sync();
        
        $saleOrder = $purchaseDeliveryOrder->getSaleOrder();
        if ($saleOrder !== null) {
            $purchaseDeliveryOrder->setVehicleModel($saleOrder->getVehicleModel());
            $currentPurchaseDeliveryOrders = $saleOrder->getPurchaseDeliveryOrders();
            $oldPurchaseDeliveryOrders = $currentPurchaseDeliveryOrders->getValues();
            if (!in_array($purchaseDeliveryOrder, $oldPurchaseDeliveryOrders)) {
                $currentPurchaseDeliveryOrders->add($purchaseDeliveryOrder);
            }
            $purchaseDeliveryOrdersCount = $currentPurchaseDeliveryOrders->count();
            $saleOrder->setRemaining($saleOrder->getQuantity() - $purchaseDeliveryOrdersCount);
        }
    }
    
    public function save(PurchaseDeliveryOrder $purchaseDeliveryOrder)
    {
        if (empty($purchaseDeliveryOrder->getId())) {
            $this->purchaseDeliveryOrderRepository->add($purchaseDeliveryOrder);
        } else {
            $this->purchaseDeliveryOrderRepository->update($purchaseDeliveryOrder);
        }
    }
    
    public function delete(PurchaseDeliveryOrder $purchaseDeliveryOrder)
    {
        $this->beforeDelete($purchaseDeliveryOrder);
        if (!empty($purchaseDeliveryOrder->getId())) {
            $this->purchaseDeliveryOrderRepository->remove($purchaseDeliveryOrder);
        }
    }
    
    protected function beforeDelete(PurchaseDeliveryOrder $purchaseDeliveryOrder)
    {
        $purchaseDeliveryOrder->sync();
        
        $saleOrder = $purchaseDeliveryOrder->getSaleOrder();
        if ($saleOrder !== null) {
            $purchaseDeliveryOrder->setVehicleModel($saleOrder->getVehicleModel());
            $currentPurchaseDeliveryOrders = $saleOrder->getPurchaseDeliveryOrders();
            $oldPurchaseDeliveryOrders = $currentPurchaseDeliveryOrders->getValues();
            if (in_array($purchaseDeliveryOrder, $oldPurchaseDeliveryOrders)) {
                $currentPurchaseDeliveryOrders->removeElement($purchaseDeliveryOrder);
            }
            $purchaseDeliveryOrdersCount = $currentPurchaseDeliveryOrders->count();
            $saleOrder->setRemaining($saleOrder->getQuantity() - $purchaseDeliveryOrdersCount);
        }
    }
}
