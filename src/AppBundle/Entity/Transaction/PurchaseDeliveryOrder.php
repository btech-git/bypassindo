<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Admin\Staff;

/**
 * @ORM\Table(name="transaction_purchase_delivery_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\PurchaseDeliveryOrderRepository")
 */
class PurchaseDeliveryOrder extends CodeNumberEntity
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
    private $reference;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(2010)
     */
    private $vehicleProductionYear;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $vehicleChassisNumber;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $vehicleMachineNumber;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $vehicleDescription;
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
     * @ORM\ManyToOne(targetEntity="SaleOrder", inversedBy="purchaseDeliveryOrders")
     * @Assert\NotNull()
     */
    private $saleOrder;
    /**
     * @ORM\OneToMany(targetEntity="ReceiveOrder", mappedBy="purchaseDeliveryOrder")
     */
    private $receiveOrders;
    
    public function __construct()
    {
        $this->receiveOrders = new ArrayCollection();
    }
    
    public function getCodeNumberConstant()
    {
        return 'PDO';
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getReference() { return $this->reference; }
    public function setReference($reference) { $this->reference = $reference; }

    public function getVehicleProductionYear() { return $this->vehicleProductionYear; }
    public function setVehicleProductionYear($vehicleProductionYear) { $this->vehicleProductionYear = $vehicleProductionYear; }

    public function getVehicleChassisNumber() { return $this->vehicleChassisNumber; }
    public function setVehicleChassisNumber($vehicleChassisNumber) { $this->vehicleChassisNumber = $vehicleChassisNumber; }

    public function getVehicleMachineNumber() { return $this->vehicleMachineNumber; }
    public function setVehicleMachineNumber($vehicleMachineNumber) { $this->vehicleMachineNumber = $vehicleMachineNumber; }

    public function getVehicleDescription() { return $this->vehicleDescription; }
    public function setVehicleDescription($vehicleDescription) { $this->vehicleDescription = $vehicleDescription; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getSaleOrder() { return $this->saleOrder; }
    public function setSaleOrder(SaleOrder $saleOrder = null) { $this->saleOrder = $saleOrder; }

    public function getReceiveOrders() { return $this->receiveOrders; }
    public function setReceiveOrders(Collection $receiveOrders) { $this->receiveOrders = $receiveOrders; }
}
