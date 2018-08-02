<?php

namespace AppBundle\Service\Transaction;

use LibBundle\Doctrine\ObjectPersister;
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
        list($staff) = array($params['staff']);
        
        if (empty($saleOrder->getId())) {
            $saleOrder->setStaffFirst($staff);
            $saleOrder->setVehicleBrand('HINO');
        }
        $saleOrder->setStaffLast($staff);
    }
    
    public function finalize(SaleOrder $saleOrder, array $params = array())
    {
        if (empty($saleOrder->getId())) {
            $transactionDate = $saleOrder->getTransactionDate();
            if ($transactionDate !== null) {
                $month = intval($transactionDate->format('m'));
                $year = intval($transactionDate->format('y'));
                $lastSaleOrderApplication = $this->saleOrderRepository->findRecentBy($year, $month);
                $currentSaleOrder = ($lastSaleOrderApplication === null) ? $saleOrder : $lastSaleOrderApplication;
                $saleOrder->setCodeNumberToNext($currentSaleOrder->getCodeNumber(), $year, $month);
            }
        }
        $this->sync($saleOrder);
    }
    
    private function sync(SaleOrder $saleOrder)
    {
        $total = $saleOrder->getQuantity() * $saleOrder->getUnitPrice();
        $saleOrder->setTotal($total);
        $saleOrder->setStaffApproval(null);
        if ($saleOrder->getIsCash() && !$saleOrder->getIsLeasing()) {
            $saleOrder->setFinanceCompany(null);
            $saleOrder->setLeasingTerm('');
            $saleOrder->setLeasingMonthlyNominal('0.00');
        }
        $purchaseDeliveryOrdersCount = $saleOrder->getPurchaseDeliveryOrders()->count();
        $saleOrder->setRemaining($saleOrder->getQuantity() - $purchaseDeliveryOrdersCount);
        $purchaseWorkshopHeader = $saleOrder->getPurchaseWorkshopHeader();
        if ($purchaseWorkshopHeader !== null) {
            $purchaseWorkshopHeader->setQuantityOrder($saleOrder->getQuantity());
        }
    }
    
    public function save(SaleOrder $saleOrder)
    {
        if (empty($saleOrder->getId())) {
            ObjectPersister::save(function() use ($saleOrder) {
                $this->saleOrderRepository->add($saleOrder);
            });
        } else {
            ObjectPersister::save(function() use ($saleOrder) {
                $this->saleOrderRepository->update($saleOrder);
            });
        }
    }
    
    public function delete(SaleOrder $saleOrder)
    {
        $this->beforeDelete($saleOrder);
        if (!empty($saleOrder->getId())) {
            ObjectPersister::save(function() use ($saleOrder) {
                $this->saleOrderRepository->remove($saleOrder);
            });
        }
    }
    
    protected function beforeDelete(SaleOrder $saleOrder)
    {
        $this->sync($saleOrder);
    }
}
