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
use AppBundle\Entity\Master\VehicleModel;

/**
 * @ORM\Table(name="transaction_sale_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\SaleOrderRepository")
 * @Assert\Expression("(this.getIsCash() and !this.getIsLeasing()) or (!this.getIsCash() and this.getIsLeasing() and this.getLeasingMonthlyNominal() > 0)")
 */
class SaleOrder extends CodeNumberEntity
{
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
    private $quotationNumber;
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    private $purchaseOrderDate;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $purchaseOrderNumber;
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    private $deliveryDate;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $invoiceRegistrationName;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isOffTheRoad;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $vehicleBrand;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $vehicleSerialNumber;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $vehicleColor;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     */
    private $vehicleOptionalInfo;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     */
    private $vehicleAccessoriesInfo;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     */
    private $vehicleOtherInfo;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $quantity;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $unitPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $total;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isCash;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isLeasing;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $leasingName;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $leasingTerm;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $leasingMonthlyNominal;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $downPayment;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\VehicleModel")
     * @Assert\NotNull()
     */
    private $vehicleModel;
    /**
     * @ORM\OneToMany(targetEntity="ReceiveOrder", mappedBy="saleOrder")
     */
    private $receiveOrders;
    
    public function __construct()
    {
        $this->receiveOrders = new ArrayCollection();
    }
    
    public function getCodeNumberConstant()
    {
        return 'SO';
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }
    
    public function getQuotationNumber() { return $this->quotationNumber; }
    public function setQuotationNumber($quotationNumber) { $this->quotationNumber = $quotationNumber; }

    public function getPurchaseOrderDate() { return $this->purchaseOrderDate; }
    public function setPurchaseOrderDate($purchaseOrderDate) { $this->purchaseOrderDate = $purchaseOrderDate; }

    public function getPurchaseOrderNumber() { return $this->purchaseOrderNumber; }
    public function setPurchaseOrderNumber($purchaseOrderNumber) { $this->purchaseOrderNumber = $purchaseOrderNumber; }

    public function getDeliveryDate() { return $this->deliveryDate; }
    public function setDeliveryDate($deliveryDate) { $this->deliveryDate = $deliveryDate; }

    public function getInvoiceRegistrationName() { return $this->invoiceRegistrationName; }
    public function setInvoiceRegistrationName($invoiceRegistrationName) { $this->invoiceRegistrationName = $invoiceRegistrationName; }

    public function getIsOffTheRoad() { return $this->isOffTheRoad; }
    public function setIsOffTheRoad($isOffTheRoad) { $this->isOffTheRoad = $isOffTheRoad; }

    public function getVehicleBrand() { return $this->vehicleBrand; }
    public function setVehicleBrand($vehicleBrand) { $this->vehicleBrand = $vehicleBrand; }

    public function getVehicleSerialNumber() { return $this->vehicleSerialNumber; }
    public function setVehicleSerialNumber($vehicleSerialNumber) { $this->vehicleSerialNumber = $vehicleSerialNumber; }

    public function getVehicleColor() { return $this->vehicleColor; }
    public function setVehicleColor($vehicleColor) { $this->vehicleColor = $vehicleColor; }

    public function getVehicleOptionalInfo() { return $this->vehicleOptionalInfo; }
    public function setVehicleOptionalInfo($vehicleOptionalInfo) { $this->vehicleOptionalInfo = $vehicleOptionalInfo; }

    public function getVehicleAccessoriesInfo() { return $this->vehicleAccessoriesInfo; }
    public function setVehicleAccessoriesInfo($vehicleAccessoriesInfo) { $this->vehicleAccessoriesInfo = $vehicleAccessoriesInfo; }

    public function getVehicleOtherInfo() { return $this->vehicleOtherInfo; }
    public function setVehicleOtherInfo($vehicleOtherInfo) { $this->vehicleOtherInfo = $vehicleOtherInfo; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getUnitPrice() { return $this->unitPrice; }
    public function setUnitPrice($unitPrice) { $this->unitPrice = $unitPrice; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getIsCash() { return $this->isCash; }
    public function setIsCash($isCash) { $this->isCash = $isCash; }

    public function getIsLeasing() { return $this->isLeasing; }
    public function setIsLeasing($isLeasing) { $this->isLeasing = $isLeasing; }

    public function getLeasingName() { return $this->leasingName; }
    public function setLeasingName($leasingName) { $this->leasingName = $leasingName; }

    public function getLeasingTerm() { return $this->leasingTerm; }
    public function setLeasingTerm($leasingTerm) { $this->leasingTerm = $leasingTerm; }

    public function getLeasingMonthlyNominal() { return $this->leasingMonthlyNominal; }
    public function setLeasingMonthlyNominal($leasingMonthlyNominal) { $this->leasingMonthlyNominal = $leasingMonthlyNominal; }

    public function getDownPayment() { return $this->downPayment; }
    public function setDownPayment($downPayment) { $this->downPayment = $downPayment; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getCustomer() { return $this->customer; }
    public function setCustomer(Customer $customer = null) { $this->customer = $customer; }

    public function getVehicleModel() { return $this->vehicleModel; }
    public function setVehicleModel(VehicleModel $vehicleModel = null) { $this->vehicleModel = $vehicleModel; }

    public function getReceiveOrders() { return $this->receiveOrders; }
    public function setReceiveOrders(Collection $receiveOrders) { $this->receiveOrders = $receiveOrders; }
}
