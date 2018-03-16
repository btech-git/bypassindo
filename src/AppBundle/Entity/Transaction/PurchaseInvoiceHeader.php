<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Admin\Staff;
use AppBundle\Entity\Master\Supplier;

/**
 * @ORM\Table(name="transaction_purchase_invoice_header")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\PurchaseInvoiceHeaderRepository")
 */
class PurchaseInvoiceHeader extends CodeNumberEntity
{
    const BUSINESS_TYPE_UNIT = 'hino motor';
    const BUSINESS_TYPE_WORKSHOP = 'karoseri';
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
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $supplierInvoiceNumber;
    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $taxInvoiceDate;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $taxInvoiceNumber;
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
     * @ORM\Column(name="sub_total", type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $subTotal;
    /**
     * @ORM\Column(name="tax_nominal", type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $taxNominal;
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
     * @ORM\Column(name="is_tax", type="boolean")
     * @Assert\NotNull()
     */
    private $isTax;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Supplier")
     * @Assert\NotNull()
     */
    private $supplier;
    /**
     * @ORM\OneToOne(targetEntity="ReceiveWorkshop", inversedBy="purchaseInvoiceHeader")
     */
    private $receiveWorkshop;
    /**
     * @ORM\OneToOne(targetEntity="PurchasePaymentHeader", mappedBy="purchaseInvoiceHeader")
     */
    private $purchasePaymentHeader;
    /**
     * @ORM\OneToMany(targetEntity="PurchaseInvoiceDetailUnit", mappedBy="purchaseInvoiceHeader")
     */
    private $purchaseInvoiceDetailUnits;
    /**
     * @ORM\OneToMany(targetEntity="PurchaseInvoiceDetailWorkshop", mappedBy="purchaseInvoiceHeader")
     */
    private $purchaseInvoiceDetailWorkshops;
    /**
     * @ORM\OneToMany(targetEntity="PurchaseInvoiceDetailGeneral", mappedBy="purchaseInvoiceHeader")
     */
    private $purchaseInvoiceDetailGenerals;
    
    public function __construct()
    {
        $this->purchaseInvoiceDetailUnits = new ArrayCollection();
        $this->purchaseInvoiceDetailWorkshops = new ArrayCollection();
        $this->purchaseInvoiceDetailGenerals = new ArrayCollection();
    }
    
    public function getCodeNumberConstant()
    {
        return 'PIN';
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getSupplierInvoiceNumber() { return $this->supplierInvoiceNumber; }
    public function setSupplierInvoiceNumber($supplierInvoiceNumber) { $this->supplierInvoiceNumber = $supplierInvoiceNumber; }

    public function getTaxInvoiceDate() { return $this->taxInvoiceDate; }
    public function setTaxInvoiceDate($taxInvoiceDate) { $this->taxInvoiceDate = $taxInvoiceDate; }

    public function getTaxInvoiceNumber() { return $this->taxInvoiceNumber; }
    public function setTaxInvoiceNumber($taxInvoiceNumber) { $this->taxInvoiceNumber = $taxInvoiceNumber; }

    public function getBusinessType() { return $this->businessType; }
    public function setBusinessType($businessType) { $this->businessType = $businessType; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getSubTotal() { return $this->subTotal; }
    public function setSubTotal($subTotal) { $this->subTotal = $subTotal; }
    
    public function getTaxNominal() { return $this->taxNominal; }
    public function setTaxNominal($taxNominal) { $this->taxNominal = $taxNominal; }
    
    public function getGrandTotal() { return $this->grandTotal; }
    public function setGrandTotal($grandTotal) { $this->grandTotal = $grandTotal; }

    public function getTotalPayment() { return $this->totalPayment; }
    public function setTotalPayment($totalPayment) { $this->totalPayment = $totalPayment; }

    public function getRemaining() { return $this->remaining; }
    public function setRemaining($remaining) { $this->remaining = $remaining; }

    public function getIsTax() { return $this->isTax; }
    public function setIsTax($isTax) { $this->isTax = $isTax; }
    
    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getSupplier() { return $this->supplier; }
    public function setSupplier(Supplier $supplier = null) { $this->supplier = $supplier; }

    public function getReceiveWorkshop() { return $this->receiveWorkshop; }
    public function setReceiveWorkshop(ReceiveWorkshop $receiveWorkshop = null) { $this->receiveWorkshop = $receiveWorkshop; }

    public function getPurchasePaymentHeader() { return $this->purchasePaymentHeader; }
    public function setPurchasePaymentHeader(PurchasePaymentHeader $purchasePaymentHeader = null) { $this->purchasePaymentHeader = $purchasePaymentHeader; }

    public function getPurchaseInvoiceDetailUnits() { return $this->purchaseInvoiceDetailUnits; }
    public function setPurchaseInvoiceDetailUnits(Collection $purchaseInvoiceDetailUnits) { $this->purchaseInvoiceDetailUnits = $purchaseInvoiceDetailUnits; }

    public function getPurchaseInvoiceDetailWorkshops() { return $this->purchaseInvoiceDetailWorkshops; }
    public function setPurchaseInvoiceDetailWorkshops(Collection $purchaseInvoiceDetailWorkshops) { $this->purchaseInvoiceDetailWorkshops = $purchaseInvoiceDetailWorkshops; }

    public function getPurchaseInvoiceDetailGenerals() { return $this->purchaseInvoiceDetailGenerals; }
    public function setPurchaseInvoiceDetailGenerals(Collection $purchaseInvoiceDetailGenerals) { $this->purchaseInvoiceDetailGenerals = $purchaseInvoiceDetailGenerals; }

    public function sync()
    {
        $subTotal = '0.00';
        foreach ($this->purchaseInvoiceDetailGenerals as $purchaseInvoiceDetailGeneral) {
            $purchaseInvoiceDetailGeneral->sync();
            $subTotal += $purchaseInvoiceDetailGeneral->getTotal();
        }
        foreach ($this->purchaseInvoiceDetailUnits as $purchaseInvoiceDetailUnit) {
            $purchaseInvoiceDetailUnit->sync();
            $subTotal += $purchaseInvoiceDetailUnit->getTotal();
        }
        foreach ($this->purchaseInvoiceDetailWorkshops as $purchaseInvoiceDetailWorkshop) {
            $purchaseInvoiceDetailWorkshop->sync();
            $subTotal += $purchaseInvoiceDetailWorkshop->getTotal();
        }
        $this->subTotal = $subTotal;
        $taxNominal = $this->getIsTax() ? $subTotal * 0.1 : 0;
        $this->taxNominal = $taxNominal;
        $grandTotal = $subTotal + $taxNominal;
        $this->grandTotal = $grandTotal;
        
        $totalPayment = '0.00';
        if ($this->purchasePaymentHeader !== null) {
            $this->purchasePaymentHeader->sync();
            $totalPayment = $this->purchasePaymentHeader->getTotalAmount();
        }
        $this->totalPayment = $totalPayment;
        
        $this->remaining = $this->grandTotal - $this->totalPayment;
    }
}
