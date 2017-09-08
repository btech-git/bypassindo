<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Admin\Staff;
use AppBundle\Entity\Master\PaymentMethod;

/**
 * @ORM\Table(name="transaction_sale_payment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\SalePaymentRepository")
 */
class SalePayment extends CodeNumberEntity
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
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $amount;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\PaymentMethod")
     * @Assert\NotNull()
     */
    private $paymentMethod;
    /**
     * @ORM\ManyToOne(targetEntity="SaleInvoice", inversedBy="salePayments")
     * @Assert\NotNull()
     */
    private $saleInvoice;
    
    public function __construct()
    {
    }
    
    public function getCodeNumberConstant()
    {
        return 'SPY';
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getAmount() { return $this->amount; }
    public function setAmount($amount) { $this->amount = $amount; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getPaymentMethod() { return $this->paymentMethod; }
    public function setPaymentMethod(PaymentMethod $paymentMethod = null) { $this->paymentMethod = $paymentMethod; }

    public function getSaleInvoice() { return $this->saleInvoice; }
    public function setSaleInvoice(SaleInvoice $saleInvoice = null) { $this->saleInvoice = $saleInvoice; }
}
