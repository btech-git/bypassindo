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
 * @ORM\Table(name="transaction_sale_invoice_header")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\SaleInvoiceHeaderRepository")
 * @UniqueEntity({"taxNumber"})
 */
class SaleInvoiceHeader extends CodeNumberEntity
{
    const BUSINESS_TYPE_UNIT = 'unit';
    const BUSINESS_TYPE_GENERAL = 'umum';
    
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
     * @ORM\Column(type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $createdDate;
    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $dueDate;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank() @Assert\Length(min=16, max=16)
     */
    private $taxNumber;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $businessType;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $grandTotal;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Customer")
     * @Assert\NotNull()
     */
    private $customer;
    /**
     * @ORM\OneToOne(targetEntity="SalePaymentHeader", mappedBy="saleInvoiceHeader")
     */
    private $salePaymentHeader;
    /**
     * @ORM\OneToMany(targetEntity="SaleInvoiceDetailUnit", mappedBy="saleInvoiceHeader")
     */
    private $saleInvoiceDetailUnits;
    /**
     * @ORM\OneToMany(targetEntity="SaleInvoiceDetailGeneral", mappedBy="saleInvoiceHeader")
     */
    private $saleInvoiceDetailGenerals;
    
    public function __construct()
    {
        $this->saleInvoiceDetailUnits = new ArrayCollection();
        $this->saleInvoiceDetailGenerals = new ArrayCollection();
    }
    
    public function getCodeNumberConstant()
    {
        return 'SINV';
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getCreatedDate() { return $this->createdDate; }
    public function setCreatedDate($createdDate) { $this->createdDate = $createdDate; }

    public function getDueDate() { return $this->dueDate; }
    public function setDueDate($dueDate) { $this->dueDate = $dueDate; }

    public function getTaxNumber() { return $this->taxNumber; }
    public function setTaxNumber($taxNumber) { $this->taxNumber = $taxNumber; }

    public function getBusinessType() { return $this->businessType; }
    public function setBusinessType($businessType) { $this->businessType = $businessType; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getGrandTotal() { return $this->grandTotal; }
    public function setGrandTotal($grandTotal) { $this->grandTotal = $grandTotal; }

    public function getTotalPayment() { return $this->totalPayment; }
    public function setTotalPayment($totalPayment) { $this->totalPayment = $totalPayment; }

    public function getRemaining() { return $this->remaining; }
    public function setRemaining($remaining) { $this->remaining = $remaining; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getCustomer() { return $this->customer; }
    public function setCustomer(Customer $customer = null) { $this->customer = $customer; }

    public function getSalePaymentHeader() { return $this->salePaymentHeader; }
    public function setSalePaymentHeader(SalePaymentHeader $salePaymentHeader = null) { $this->salePaymentHeader = $salePaymentHeader; }

    public function getSaleInvoiceDetailUnits() { return $this->saleInvoiceDetailUnits; }
    public function setSaleInvoiceDetailUnits(Collection $saleInvoiceDetailUnits) { $this->saleInvoiceDetailUnits = $saleInvoiceDetailUnits; }

    public function getSaleInvoiceDetailGenerals() { return $this->saleInvoiceDetailGenerals; }
    public function setSaleInvoiceDetailGenerals(Collection $saleInvoiceDetailGenerals) { $this->saleInvoiceDetailGenerals = $saleInvoiceDetailGenerals; }

    public function getFormattedTaxNumber()
    {
        $part1 = substr($this->taxNumber, 0, 3);
        $part2 = substr($this->taxNumber, 3, 3);
        $part3 = substr($this->taxNumber, 6, 2);
        $part4 = substr($this->taxNumber, 8, 8);
        return $part1 . '.' . $part2 . '-' . $part3 . '.' . $part4;
    }

    public function sync()
    {
        $grandTotal = 0.00;
        foreach ($this->saleInvoiceDetailUnits as $saleInvoiceDetailUnit) {
            $saleInvoiceDetailUnit->sync();
            $grandTotal += $saleInvoiceDetailUnit->getTotal();
        }
        foreach ($this->saleInvoiceDetailGenerals as $saleInvoiceDetailGeneral) {
            $saleInvoiceDetailGeneral->sync();
            $grandTotal += $saleInvoiceDetailGeneral->getTotal();
        }
        $this->grandTotal = $grandTotal;
        
        $totalPayment = '0.00';
        if ($this->salePaymentHeader !== null) {
            $totalPayment = $this->salePaymentHeader->getTotalAmount();
        }
        $this->totalPayment = $totalPayment;
        
        $this->remaining = $this->grandTotal - $this->totalPayment;
    }
}
