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
        list($date, $staff) = array($params['date'], $params['staff']);
        
        if (empty($saleInvoiceHeader->getId())) {
            $createdDate = date_create_from_format('Y-m-d', $date);
            $saleInvoiceHeader->setCreatedDate($createdDate);
            $saleInvoiceHeader->setStaffFirst($staff);
        }
        $saleInvoiceHeader->setStaffLast($staff);
    }
    
    public function finalize(SaleInvoiceHeader $saleInvoiceHeader, array $params = array())
    {
        if (empty($saleInvoiceHeader->getId())) {
            $transactionDate = $saleInvoiceHeader->getTransactionDate();
            if ($transactionDate !== null) {
                $month = intval($transactionDate->format('m'));
                $year = intval($transactionDate->format('y'));
                $lastSaleInvoiceHeaderApplication = $this->saleInvoiceHeaderRepository->findRecentBy($year, $month);
                $currentSaleInvoiceHeader = ($lastSaleInvoiceHeaderApplication === null) ? $saleInvoiceHeader : $lastSaleInvoiceHeaderApplication;
                $saleInvoiceHeader->setCodeNumberToNext($currentSaleInvoiceHeader->getCodeNumber(), $year, $month);
            }
        }
        foreach ($saleInvoiceHeader->getSaleInvoiceDetails() as $saleInvoiceDetail) {
            $saleInvoiceDetail->setSaleInvoiceHeader($saleInvoiceHeader);
        }
        $this->sync($saleInvoiceHeader);
    }
    
    private function sync(SaleInvoiceHeader $saleInvoiceHeader)
    {
        $customer = $saleInvoiceHeader->getCustomer();
        $transactionDate = $saleInvoiceHeader->getTransactionDate();
        if ($transactionDate !== null && $customer !== null) {
            $creditPaymentTerm = $customer->getCreditPaymentTerm();
            $saleInvoiceHeader->setDueDate($transactionDate->add(date_interval_create_from_date_string("{$creditPaymentTerm} days")));
        }
        foreach ($saleInvoiceHeader->getSaleInvoiceDetails() as $saleInvoiceDetail) {
            $purchaseDeliveryOrder = $saleInvoiceDetail->getReceiveOrder()->getPurchaseDeliveryOrder();
            $saleInvoiceDetail->setVehicleChassisNumber($purchaseDeliveryOrder->getVehicleChassisNumber());
            $saleInvoiceDetail->setVehicleMachineNumber($purchaseDeliveryOrder->getVehicleMachineNumber());
            $saleInvoiceDetail->setQuantity(1);
            $saleInvoiceDetail->setUnitPrice($purchaseDeliveryOrder->getSaleOrder()->getUnitPrice());
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
