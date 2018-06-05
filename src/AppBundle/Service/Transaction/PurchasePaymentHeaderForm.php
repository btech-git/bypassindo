<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\PurchasePaymentHeader;
use AppBundle\Repository\Transaction\PurchasePaymentHeaderRepository;

class PurchasePaymentHeaderForm
{
    private $purchasePaymentHeaderRepository;
    
    public function __construct(PurchasePaymentHeaderRepository $purchasePaymentHeaderRepository)
    {
        $this->purchasePaymentHeaderRepository = $purchasePaymentHeaderRepository;
    }
    
    public function initialize(PurchasePaymentHeader $purchasePaymentHeader, array $params = array())
    {
        list($staff) = array($params['staff']);
        
        if (empty($purchasePaymentHeader->getId())) {
            $purchasePaymentHeader->setStaffFirst($staff);
        }
        $purchasePaymentHeader->setStaffLast($staff);
    }
    
    public function finalize(PurchasePaymentHeader $purchasePaymentHeader, array $params = array())
    {
        if (empty($purchasePaymentHeader->getId())) {
            $transactionDate = $purchasePaymentHeader->getTransactionDate();
            if ($transactionDate !== null) {
                $month = intval($transactionDate->format('m'));
                $year = intval($transactionDate->format('y'));
                $lastPurchasePaymentHeaderApplication = $this->purchasePaymentHeaderRepository->findRecentBy($year, $month);
                $currentPurchasePaymentHeader = ($lastPurchasePaymentHeaderApplication === null) ? $purchasePaymentHeader : $lastPurchasePaymentHeaderApplication;
                $purchasePaymentHeader->setCodeNumberToNext($currentPurchasePaymentHeader->getCodeNumber(), $year, $month);
            }
        }
        foreach ($purchasePaymentHeader->getPurchasePaymentDetails() as $purchasePaymentDetail) {
            $purchasePaymentDetail->setPurchasePaymentHeader($purchasePaymentHeader);
        }
        $this->sync($purchasePaymentHeader);
    }
    
    private function sync(PurchasePaymentHeader $purchasePaymentHeader)
    {
        $totalAmount = 0.00;
        foreach ($purchasePaymentHeader->getPurchasePaymentDetails() as $purchasePaymentDetail) {
            $totalAmount += $purchasePaymentDetail->getAmount();
        }
        $purchasePaymentHeader->setTotalAmount($totalAmount);
    }
    
    public function save(PurchasePaymentHeader $purchasePaymentHeader)
    {
        if (empty($purchasePaymentHeader->getId())) {
            $this->purchasePaymentHeaderRepository->add($purchasePaymentHeader, array(
                'purchasePaymentDetails' => array('add' => true),
            ));
        } else {
            $this->purchasePaymentHeaderRepository->update($purchasePaymentHeader, array(
                'purchasePaymentDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(PurchasePaymentHeader $purchasePaymentHeader)
    {
        $this->beforeDelete($purchasePaymentHeader);
        if (!empty($purchasePaymentHeader->getId())) {
            $this->purchasePaymentHeaderRepository->remove($purchasePaymentHeader, array(
                'purchasePaymentDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(PurchasePaymentHeader $purchasePaymentHeader)
    {
        $purchasePaymentHeader->getPurchasePaymentDetails()->clear();
        $this->sync($purchasePaymentHeader);
    }
}
