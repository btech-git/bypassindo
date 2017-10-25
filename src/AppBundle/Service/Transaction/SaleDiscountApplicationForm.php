<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SaleDiscountApplication;
use AppBundle\Repository\Transaction\SaleDiscountApplicationRepository;

class SaleDiscountApplicationForm
{
    private $saleDiscountApplicationRepository;
    
    public function __construct(SaleDiscountApplicationRepository $saleDiscountApplicationRepository)
    {
        $this->saleDiscountApplicationRepository = $saleDiscountApplicationRepository;
    }
    
    public function initialize(SaleDiscountApplication $saleDiscountApplication, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($saleDiscountApplication->getId())) {
            $lastSaleDiscountApplication = $this->saleDiscountApplicationRepository->findRecentBy($year, $month);
            $currentSaleDiscountApplication = ($lastSaleDiscountApplication === null) ? $saleDiscountApplication : $lastSaleDiscountApplication;
            $saleDiscountApplication->setCodeNumberToNext($currentSaleDiscountApplication->getCodeNumber(), $year, $month);
            
            $saleDiscountApplication->setApprovedPrice('0.00');
            $saleDiscountApplication->setStaffFirst($staff);
        }
        $saleDiscountApplication->setStaffLast($staff);
    }
    
    public function finalize(SaleDiscountApplication $saleDiscountApplication, array $params = array())
    {
        $this->sync($saleDiscountApplication);
    }
    
    private function sync(SaleDiscountApplication $saleDiscountApplication)
    {
        $subTotal = $saleDiscountApplication->getOffTheRoadPrice() + $saleDiscountApplication->getRegistrationPrice();
        $grandTotal = $subTotal + $saleDiscountApplication->getOtherPricingAmount1() + $saleDiscountApplication->getOtherPricingAmount2() + $saleDiscountApplication->getOtherPricingAmount3() + $saleDiscountApplication->getOtherPricingAmount4() + $saleDiscountApplication->getOtherPricingAmount5();
        $saleDiscountApplication->setSubTotalPrice($subTotal);
        $saleDiscountApplication->setGrandTotalPrice($grandTotal);
        $customerStatusType = $saleDiscountApplication->getCustomerStatusType();
        if ($customerStatusType !== SaleDiscountApplication::CUSTOMER_STATUS_OTHER) {
            $saleDiscountApplication->setCustomerStatusName($customerStatusType);
        }
        $paymentMethodType = $saleDiscountApplication->getPaymentMethodType();
        if ($paymentMethodType !== SaleDiscountApplication::PAYMENT_METHOD_FINANCE_COMPANY) {
            $saleDiscountApplication->setFinanceCompany(null);
        }
    }
    
    public function save(SaleDiscountApplication $saleDiscountApplication)
    {
        if (empty($saleDiscountApplication->getId())) {
            $this->saleDiscountApplicationRepository->add($saleDiscountApplication);
        } else {
            $this->saleDiscountApplicationRepository->update($saleDiscountApplication);
        }
    }
    
    public function delete(SaleDiscountApplication $saleDiscountApplication)
    {
        $this->beforeDelete($saleDiscountApplication);
        if (!empty($saleDiscountApplication->getId())) {
            $this->saleDiscountApplicationRepository->remove($saleDiscountApplication);
        }
    }
    
    protected function beforeDelete(SaleDiscountApplication $saleDiscountApplication)
    {
        $this->sync($saleDiscountApplication);
    }
}
