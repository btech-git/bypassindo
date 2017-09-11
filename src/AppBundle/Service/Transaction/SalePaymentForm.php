<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SalePayment;
use AppBundle\Repository\Transaction\SalePaymentRepository;

class SalePaymentForm
{
    private $salePaymentRepository;
    
    public function __construct(SalePaymentRepository $salePaymentRepository)
    {
        $this->salePaymentRepository = $salePaymentRepository;
    }
    
    public function initialize(SalePayment $salePayment, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($salePayment->getId())) {
            $lastSalePayment = $this->salePaymentRepository->findRecentBy($year, $month);
            $currentSalePayment = ($lastSalePayment === null) ? $salePayment : $lastSalePayment;
            $salePayment->setCodeNumberToNext($currentSalePayment->getCodeNumber(), $year, $month);
            
            $salePayment->setStaffFirst($staff);
        }
        $salePayment->setStaffLast($staff);
    }
    
    public function finalize(SalePayment $salePayment, array $params = array())
    {
        $this->sync($salePayment);
    }
    
    private function sync(SalePayment $salePayment)
    {
        $saleInvoice = $salePayment->getSaleInvoice();
        if ($saleInvoice !== null) {
            $saleInvoice->sync();
        }
    }
    
    public function save(SalePayment $salePayment)
    {
        if (empty($salePayment->getId())) {
            $this->salePaymentRepository->add($salePayment);
        } else {
            $this->salePaymentRepository->update($salePayment);
        }
    }
    
    public function delete(SalePayment $salePayment)
    {
        $this->beforeDelete($salePayment);
        if (!empty($salePayment->getId())) {
            $this->salePaymentRepository->remove($salePayment);
        }
    }
    
    protected function beforeDelete(SalePayment $salePayment)
    {
        $this->sync($salePayment);
    }
}
