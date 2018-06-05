<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SaleInvoiceDownpayment;
use AppBundle\Repository\Transaction\SaleInvoiceDownpaymentRepository;

class SaleInvoiceDownpaymentForm
{
    private $saleInvoiceDownpaymentRepository;
    
    public function __construct(SaleInvoiceDownpaymentRepository $saleInvoiceDownpaymentRepository)
    {
        $this->saleInvoiceDownpaymentRepository = $saleInvoiceDownpaymentRepository;
    }
    
    public function initialize(SaleInvoiceDownpayment $saleInvoiceDownpayment, array $params = array())
    {
        list($staff) = array($params['staff']);
        
        if (empty($saleInvoiceDownpayment->getId())) {
            $saleInvoiceDownpayment->setStaffFirst($staff);
        }
        $saleInvoiceDownpayment->setStaffLast($staff);
    }
    
    public function finalize(SaleInvoiceDownpayment $saleInvoiceDownpayment, array $params = array())
    {
        if (empty($saleInvoiceDownpayment->getId())) {
            $transactionDate = $saleInvoiceDownpayment->getTransactionDate();
            if ($transactionDate !== null) {
                $month = intval($transactionDate->format('m'));
                $year = intval($transactionDate->format('y'));
                $lastSaleInvoiceDownpaymentApplication = $this->saleInvoiceDownpaymentRepository->findRecentBy($year, $month);
                $currentSaleInvoiceDownpayment = ($lastSaleInvoiceDownpaymentApplication === null) ? $saleInvoiceDownpayment : $lastSaleInvoiceDownpaymentApplication;
                $saleInvoiceDownpayment->setCodeNumberToNext($currentSaleInvoiceDownpayment->getCodeNumber(), $year, $month);
            }
        }
        
        $this->sync($saleInvoiceDownpayment);
    }
    
    private function sync(SaleInvoiceDownpayment $saleInvoiceDownpayment)
    {
        $saleOrder = $saleInvoiceDownpayment->getSaleOrder();
        if ($saleOrder !== null) {
            $saleInvoiceDownpayment->setCustomer($saleOrder->getCustomer());
        }
        
        $saleInvoiceDownpayment->sync();
    }
    
    public function save(SaleInvoiceDownpayment $saleInvoiceDownpayment)
    {
        if (empty($saleInvoiceDownpayment->getId())) {
            $this->saleInvoiceDownpaymentRepository->add($saleInvoiceDownpayment);
        } else {
            $this->saleInvoiceDownpaymentRepository->update($saleInvoiceDownpayment);
        }
    }
    
    public function delete(SaleInvoiceDownpayment $saleInvoiceDownpayment)
    {
        $this->beforeDelete($saleInvoiceDownpayment);
        if (!empty($saleInvoiceDownpayment->getId())) {
            $this->saleInvoiceDownpaymentRepository->remove($saleInvoiceDownpayment);
        }
    }
    
    protected function beforeDelete(SaleInvoiceDownpayment $saleInvoiceDownpayment)
    {
        $this->sync($saleInvoiceDownpayment);
    }
}
