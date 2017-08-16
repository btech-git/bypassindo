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
    const CUSTOMER_STATUS_HINO = 'hino';
    const CUSTOMER_STATUS_MITSUBISHI = 'mitsubishi';
    const CUSTOMER_STATUS_NISSAN = 'nissan';
    const CUSTOMER_STATUS_ISUZU = 'isuzu';
    const CUSTOMER_STATUS_BENZ = 'benz';
    const CUSTOMER_STATUS_OTHER = 'other';
    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_BANK = 'bank';
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
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $customerStatusType;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $customerStatusName;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $requestType;
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
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $requestWorkshop;
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
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $paymentMethodValue;
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
     * @Assert\NotNull() @Assert\GreaterThan(0)
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
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $otherPrice;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Customer", inversedBy="saleDiscountApplications")
     * @Assert\NotNull()
     */
    private $customer;
    
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

    public function getCustomerStatusType() { return $this->customerStatusType; }
    public function setCustomerStatusType($customerStatusType) { $this->customerStatusType = $customerStatusType; }

    public function getCustomerStatusName() { return $this->customerStatusName; }
    public function setCustomerStatusName($customerStatusName) { $this->customerStatusName = $customerStatusName; }

    public function getRequestType() { return $this->requestType; }
    public function setRequestType($requestType) { $this->requestType = $requestType; }

    public function getRequestQuantity() { return $this->requestQuantity; }
    public function setRequestQuantity($requestQuantity) { $this->requestQuantity = $requestQuantity; }

    public function getRequestUsageType() { return $this->requestUsageType; }
    public function setRequestUsageType($requestUsageType) { $this->requestUsageType = $requestUsageType; }

    public function getRequestWorkshop() { return $this->requestWorkshop; }
    public function setRequestWorkshop($requestWorkshop) { $this->requestWorkshop = $requestWorkshop; }

    public function getCompetitorBrand() { return $this->competitorBrand; }
    public function setCompetitorBrand($competitorBrand) { $this->competitorBrand = $competitorBrand; }

    public function getCompetitorType() { return $this->competitorType; }
    public function setCompetitorType($competitorType) { $this->competitorType = $competitorType; }

    public function getCompetitorDealer() { return $this->competitorDealer; }
    public function setCompetitorDealer($competitorDealer) { $this->competitorDealer = $competitorDealer; }

    public function getPaymentMethodType() { return $this->paymentMethodType; }
    public function setPaymentMethodType($paymentMethodType) { $this->paymentMethodType = $paymentMethodType; }

    public function getPaymentMethodValue() { return $this->paymentMethodValue; }
    public function setPaymentMethodValue($paymentMethodValue) { $this->paymentMethodValue = $paymentMethodValue; }

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

    public function getOtherPrice() { return $this->otherPrice; }
    public function setOtherPrice($otherPrice) { $this->otherPrice = $otherPrice; }

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
}
