<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Admin\Staff;
use AppBundle\Entity\Master\Customer;
use AppBundle\Entity\Master\FinanceCompany;
use AppBundle\Entity\Master\VehicleModel;
use AppBundle\Entity\Master\Supplier;

/**
 * @ORM\Table(name="transaction_sale_discount_application")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\SaleDiscountApplicationRepository")
 */
class SaleDiscountApplication extends CodeNumberEntity
{
    const DEAL_STATUS_TOUCH = 'touch';
    const DEAL_STATUS_NEGOTIATION = 'negotiation';
    const DEAL_STATUS_HOT = 'hot';
    const DEAL_STATUS_CONTRACT = 'contract';
    const CUSTOMER_STATUS_TYPE_HINO = 'hino';
    const CUSTOMER_STATUS_TYPE_MITSUBISHI = 'mitsubishi';
    const CUSTOMER_STATUS_TYPE_NISSAN = 'u d. trucks';
    const CUSTOMER_STATUS_TYPE_ISUZU = 'isuzu';
    const CUSTOMER_STATUS_TYPE_BENZ = 'benz';
    const CUSTOMER_STATUS_TYPE_OTHER = 'other';
    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_FINANCE_COMPANY = 'finance company';
    
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $transactionDate;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $dealStatus;
    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $dealDate;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isHinoCustomer;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isMitsubishiCustomer;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isNissanCustomer;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isIsuzuCustomer;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isBenzCustomer;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $otherBrandCustomer;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $requestQuantity;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $requestUsageType;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $competitorBrand;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $competitorType;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $competitorDealer;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $paymentMethodType;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $customerPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $salesmanPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $approvedPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $competitorPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $offTheRoadPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $registrationPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $subTotalPrice;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $otherPricingName1;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $otherPricingName2;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $otherPricingName3;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $otherPricingName4;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $otherPricingName5;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $otherPricingAmount1;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $otherPricingAmount2;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $otherPricingAmount3;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $otherPricingAmount4;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $otherPricingAmount5;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $grandTotalPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $mediatorPrice;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Admin\Staff")
     * @Assert\NotNull()
     */
    private $staffFirst;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Admin\Staff")
     * @Assert\NotNull()
     */
    private $staffLast;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Customer")
     * @Assert\NotNull()
     */
    private $customer;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\FinanceCompany")
     */
    private $financeCompany;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\VehicleModel")
     * @Assert\NotNull()
     */
    private $vehicleModel;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Supplier")
     */
    private $supplier;
    
    public function __construct()
    {
    }
    
    public function getCodeNumberConstant()
    {
        return 'SDC';
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getDealStatus() { return $this->dealStatus; }
    public function setDealStatus($dealStatus) { $this->dealStatus = $dealStatus; }

    public function getDealDate() { return $this->dealDate; }
    public function setDealDate($dealDate) { $this->dealDate = $dealDate; }

    public function getIsHinoCustomer() { return $this->isHinoCustomer; }
    public function setIsHinoCustomer($isHinoCustomer) { $this->isHinoCustomer = $isHinoCustomer; }

    public function getIsMitsubishiCustomer() { return $this->isMitsubishiCustomer; }
    public function setIsMitsubishiCustomer($isMitsubishiCustomer) { $this->isMitsubishiCustomer = $isMitsubishiCustomer; }

    public function getIsNissanCustomer() { return $this->isNissanCustomer; }
    public function setIsNissanCustomer($isNissanCustomer) { $this->isNissanCustomer = $isNissanCustomer; }

    public function getIsIsuzuCustomer() { return $this->isIsuzuCustomer; }
    public function setIsIsuzuCustomer($isIsuzuCustomer) { $this->isIsuzuCustomer = $isIsuzuCustomer; }

    public function getIsBenzCustomer() { return $this->isBenzCustomer; }
    public function setIsBenzCustomer($isBenzCustomer) { $this->isBenzCustomer = $isBenzCustomer; }

    public function getOtherBrandCustomer() { return $this->otherBrandCustomer; }
    public function setOtherBrandCustomer($otherBrandCustomer) { $this->otherBrandCustomer = $otherBrandCustomer; }

    public function getRequestQuantity() { return $this->requestQuantity; }
    public function setRequestQuantity($requestQuantity) { $this->requestQuantity = $requestQuantity; }

    public function getRequestUsageType() { return $this->requestUsageType; }
    public function setRequestUsageType($requestUsageType) { $this->requestUsageType = $requestUsageType; }

    public function getCompetitorBrand() { return $this->competitorBrand; }
    public function setCompetitorBrand($competitorBrand) { $this->competitorBrand = $competitorBrand; }

    public function getCompetitorType() { return $this->competitorType; }
    public function setCompetitorType($competitorType) { $this->competitorType = $competitorType; }

    public function getCompetitorDealer() { return $this->competitorDealer; }
    public function setCompetitorDealer($competitorDealer) { $this->competitorDealer = $competitorDealer; }

    public function getPaymentMethodType() { return $this->paymentMethodType; }
    public function setPaymentMethodType($paymentMethodType) { $this->paymentMethodType = $paymentMethodType; }

    public function getCustomerPrice() { return $this->customerPrice; }
    public function setCustomerPrice($customerPrice) { $this->customerPrice = $customerPrice; }

    public function getSalesmanPrice() { return $this->salesmanPrice; }
    public function setSalesmanPrice($salesmanPrice) { $this->salesmanPrice = $salesmanPrice; }

    public function getApprovedPrice() { return $this->approvedPrice; }
    public function setApprovedPrice($approvedPrice) { $this->approvedPrice = $approvedPrice; }

    public function getCompetitorPrice() { return $this->competitorPrice; }
    public function setCompetitorPrice($competitorPrice) { $this->competitorPrice = $competitorPrice; }

    public function getOffTheRoadPrice() { return $this->offTheRoadPrice; }
    public function setOffTheRoadPrice($offTheRoadPrice) { $this->offTheRoadPrice = $offTheRoadPrice; }

    public function getRegistrationPrice() { return $this->registrationPrice; }
    public function setRegistrationPrice($registrationPrice) { $this->registrationPrice = $registrationPrice; }

    public function getSubTotalPrice() { return $this->subTotalPrice; }
    public function setSubTotalPrice($subTotalPrice) { $this->subTotalPrice = $subTotalPrice; }

    public function getOtherPricingName1() { return $this->otherPricingName1; }
    public function setOtherPricingName1($otherPricingName1) { $this->otherPricingName1 = $otherPricingName1; }

    public function getOtherPricingName2() { return $this->otherPricingName2; }
    public function setOtherPricingName2($otherPricingName2) { $this->otherPricingName2 = $otherPricingName2; }

    public function getOtherPricingName3() { return $this->otherPricingName3; }
    public function setOtherPricingName3($otherPricingName3) { $this->otherPricingName3 = $otherPricingName3; }

    public function getOtherPricingName4() { return $this->otherPricingName4; }
    public function setOtherPricingName4($otherPricingName4) { $this->otherPricingName4 = $otherPricingName4; }

    public function getOtherPricingName5() { return $this->otherPricingName5; }
    public function setOtherPricingName5($otherPricingName5) { $this->otherPricingName5 = $otherPricingName5; }

    public function getOtherPricingAmount1() { return $this->otherPricingAmount1; }
    public function setOtherPricingAmount1($otherPricingAmount1) { $this->otherPricingAmount1 = $otherPricingAmount1; }

    public function getOtherPricingAmount2() { return $this->otherPricingAmount2; }
    public function setOtherPricingAmount2($otherPricingAmount2) { $this->otherPricingAmount2 = $otherPricingAmount2; }

    public function getOtherPricingAmount3() { return $this->otherPricingAmount3; }
    public function setOtherPricingAmount3($otherPricingAmount3) { $this->otherPricingAmount3 = $otherPricingAmount3; }

    public function getOtherPricingAmount4() { return $this->otherPricingAmount4; }
    public function setOtherPricingAmount4($otherPricingAmount4) { $this->otherPricingAmount4 = $otherPricingAmount4; }

    public function getOtherPricingAmount5() { return $this->otherPricingAmount5; }
    public function setOtherPricingAmount5($otherPricingAmount5) { $this->otherPricingAmount5 = $otherPricingAmount5; }

    public function getGrandTotalPrice() { return $this->grandTotalPrice; }
    public function setGrandTotalPrice($grandTotalPrice) { $this->grandTotalPrice = $grandTotalPrice; }

    public function getMediatorPrice() { return $this->mediatorPrice; }
    public function setMediatorPrice($mediatorPrice) { $this->mediatorPrice = $mediatorPrice; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getCustomer() { return $this->customer; }
    public function setCustomer(Customer $customer = null) { $this->customer = $customer; }

    public function getFinanceCompany() { return $this->financeCompany; }
    public function setFinanceCompany(FinanceCompany $financeCompany = null) { $this->financeCompany = $financeCompany; }
    
    public function getVehicleModel() { return $this->vehicleModel; }
    public function setVehicleModel(VehicleModel $vehicleModel = null) { $this->vehicleModel = $vehicleModel; }
    
    public function getSupplier() { return $this->supplier; }
    public function setSupplier(Supplier $supplier = null) { $this->supplier = $supplier; }
}
