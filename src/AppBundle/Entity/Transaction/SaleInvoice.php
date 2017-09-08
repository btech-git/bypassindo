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
 * @ORM\Table(name="transaction_sale_invoice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\SaleInvoiceRepository")
 * @UniqueEntity({"taxNumberPrefix", "taxNumber"})
 */
class SaleInvoice extends CodeNumberEntity
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
     * @Assert\NotBlank()
     */
    private $taxNumberPrefix;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $taxNumber;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $amount;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $totalPayment;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $remaining;
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
     * @ORM\OneToMany(targetEntity="SalePayment", mappedBy="saleInvoice")
     */
    private $salePayments;
    /**
     * @ORM\OneToOne(targetEntity="ReceiveOrder", inversedBy="saleInvoice")
     * @Assert\NotNull()
     */
    private $receiveOrder;
    
    public function __construct()
    {
        $this->salePayments = new ArrayCollection();
    }
    
    public function getCodeNumberConstant()
    {
        return 'SIN';
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getTaxNumberPrefix() { return $this->taxNumberPrefix; }
    public function setTaxNumberPrefix($taxNumberPrefix) { $this->taxNumberPrefix = $taxNumberPrefix; }

    public function getTaxNumber() { return $this->taxNumber; }
    public function setTaxNumber($taxNumber) { $this->taxNumber = $taxNumber; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getAmount() { return $this->amount; }
    public function setAmount($amount) { $this->amount = $amount; }

    public function getTotalPayment() { return $this->totalPayment; }
    public function setTotalPayment($totalPayment) { $this->totalPayment = $totalPayment; }

    public function getRemaining() { return $this->remaining; }
    public function setRemaining($remaining) { $this->remaining = $remaining; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getSalePayments() { return $this->salePayments; }
    public function setSalePayments(Collection $salePayments) { $this->salePayments = $salePayments; }

    public function getReceiveOrder() { return $this->receiveOrder; }
    public function setReceiveOrder(ReceiveOrder $receiveOrder = null) { $this->receiveOrder = $receiveOrder; }
}
