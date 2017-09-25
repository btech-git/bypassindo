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
 * @Assert\Expression("(this.getIsPurchaseWorkshopHeader() and this.getPurchaseWorkshopHeader() != null) or (!this.getIsPurchaseWorkshopHeader() and this.getPurchaseWorkshopHeader() == null)")
 */
class PurchaseInvoiceHeader extends CodeNumberEntity
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
    private $supplierInvoiceNumber;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isPurchaseWorkshopHeader;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Supplier")
     * @Assert\NotNull()
     */
    private $supplier;
    /**
     * @ORM\ManyToOne(targetEntity="PurchaseWorkshopHeader", inversedBy="purchaseInvoiceHeaders")
     */
    private $purchaseWorkshopHeader;
    /**
     * @ORM\OneToMany(targetEntity="PurchasePaymentHeader", mappedBy="purchaseInvoiceHeader")
     */
    private $purchasePaymentHeaders;
    /**
     * @ORM\OneToMany(targetEntity="PurchaseInvoiceDetail", mappedBy="purchaseInvoiceHeader")
     * @Assert\Valid() @Assert\Count(min=1)
     */
    private $purchaseInvoiceDetails;
    
    public function __construct()
    {
        $this->purchasePaymentHeaders = new ArrayCollection();
        $this->purchaseInvoiceDetails = new ArrayCollection();
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

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getIsPurchaseWorkshopHeader() { return $this->isPurchaseWorkshopHeader; }
    public function setIsPurchaseWorkshopHeader($isPurchaseWorkshopHeader = null) { $this->isPurchaseWorkshopHeader = $isPurchaseWorkshopHeader; }

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

    public function getSupplier() { return $this->supplier; }
    public function setSupplier(Supplier $supplier = null) { $this->supplier = $supplier; }

    public function getPurchaseWorkshopHeader() { return $this->purchaseWorkshopHeader; }
    public function setPurchaseWorkshopHeader(PurchaseWorkshopHeader $purchaseWorkshopHeader = null) { $this->purchaseWorkshopHeader = $purchaseWorkshopHeader; }

    public function getPurchasePaymentHeaders() { return $this->purchasePaymentHeaders; }
    public function setPurchasePaymentHeaders(Collection $purchasePaymentHeaders) { $this->purchasePaymentHeaders = $purchasePaymentHeaders; }

    public function getPurchaseInvoiceDetails() { return $this->purchaseInvoiceDetails; }
    public function setPurchaseInvoiceDetails(Collection $purchaseInvoiceDetails) { $this->purchaseInvoiceDetails = $purchaseInvoiceDetails; }

    public function sync()
    {
        $grandTotal = '0.00';
        foreach ($this->purchaseInvoiceDetails as $purchaseInvoiceDetail) {
            $purchaseInvoiceDetail->sync();
            $grandTotal += $purchaseInvoiceDetail->getTotal();
        }
        $this->grandTotal = $grandTotal;
        
        $totalPayment = '0.00';
        foreach ($this->purchasePaymentHeaders as $purchasePaymentHeader) {
            $purchasePaymentHeader->sync();
            $totalPayment += $purchasePaymentHeader->getTotalAmount();
        }
        $this->totalPayment = $totalPayment;
        
        $this->remaining = $this->grandTotal - $this->totalPayment;
    }
}
