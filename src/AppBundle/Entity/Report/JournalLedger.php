<?php

namespace AppBundle\Entity\Report;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Master\Account;
use AppBundle\Entity\Admin\Staff;
use AppBundle\Entity\Transaction\PurchaseDeliveryOrder;

/**
 * @ORM\Table(name="report_journal_ledger")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Report\JournalLedgerRepository")
 */
class JournalLedger extends CodeNumberEntity
{
    const TRANSACTION_TYPE_DEPOSIT = 'deposit';
    const TRANSACTION_TYPE_EXPENSE = 'expense';
    const TRANSACTION_TYPE_VOUCHER = 'voucher';
    const TRANSACTION_TYPE_SALE_DOWNPAYMENT = 'downpayment';
    const TRANSACTION_TYPE_RECEIVABLE = 'piutang';
    const TRANSACTION_TYPE_PAYABLE = 'hutang';
    const TRANSACTION_TYPE_RECEIVABLE_PAYMENT = 'pelunasan piutang';
    const TRANSACTION_TYPE_PAYABLE_PAYMENT = 'pembayaran hutang';
    const TRANSACTION_CATEGORY_UNIT = 'unit';
    const TRANSACTION_CATEGORY_GENERAL = 'umum';
    
    /**
     * @ORM\Column(name="id", type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(name="transaction_date", type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $transactionDate;
    /**
     * @ORM\Column(name="transaction_type", type="string", length=20)
     * @Assert\NotBlank()
     */
    private $transactionType;
    /**
     * @ORM\Column(name="transaction_category", type="string", length=20)
     * @Assert\NotBlank()
     */
    private $transactionCategory;
    /**
     * @ORM\Column(name="transaction_subject", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $transactionSubject;
    /**
     * @ORM\Column(name="note", type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\Column(name="debit", type="decimal", precision=18, scale=2)
     * @Assert\NotNull()
     */
    private $debit;
    /**
     * @ORM\Column(name="credit", type="decimal", precision=18, scale=2)
     * @Assert\NotNull()
     */
    private $credit;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Account", inversedBy="journalLedgers")
     * @Assert\NotNull()
     */
    private $account;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Transaction\PurchaseDeliveryOrder", inversedBy="journalLedgers")
     */
    private $purchaseDeliveryOrder;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Admin\Staff")
     * @Assert\NotNull()
     */
    private $staff;
    
    public function getCodeNumberConstant()
    {
        
    }
    
    public function getId() { return $this->id; }
    
    public function getTransactionOrdinal() { return $this->transactionOrdinal; }
    public function setTransactionOrdinal($transactionOrdinal) { $this->transactionOrdinal = $transactionOrdinal; }
    
    public function getTransactionMonth() { return $this->transactionMonth; }
    public function setTransactionMonth($transactionMonth) { $this->transactionMonth = $transactionMonth; }
    
    public function getTransactionYear() { return $this->transactionYear; }
    public function setTransactionYear($transactionYear) { $this->transactionYear = $transactionYear; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate(\DateTime $transactionDate = null) { $this->transactionDate = $transactionDate; }
    
    public function getTransactionType() { return $this->transactionType; }
    public function setTransactionType($transactionType) { $this->transactionType = $transactionType; }
    
    public function getTransactionCategory() { return $this->transactionCategory; }
    public function setTransactionCategory($transactionCategory) { $this->transactionCategory = $transactionCategory; }
    
    public function getTransactionSubject() { return $this->transactionSubject; }
    public function setTransactionSubject($transactionSubject) { $this->transactionSubject = $transactionSubject; }
    
    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }
    
    public function getDebit() { return $this->debit; }
    public function setDebit($debit) { $this->debit = $debit; }
    
    public function getCredit() { return $this->credit; }
    public function setCredit($credit) { $this->credit = $credit; }
    
    public function getAccount() { return $this->account; }
    public function setAccount(Account $account = null) { $this->account = $account; }
    
    public function getPurchaseDeliveryOrder() { return $this->purchaseDeliveryOrder; }
    public function setPurchaseDeliveryOrder(PurchaseDeliveryOrder $purchaseDeliveryOrder = null) { $this->purchaseDeliveryOrder = $purchaseDeliveryOrder; }
    
    public function getStaff() { return $this->staff; }
    public function setStaff(Staff $staff = null) { $this->staff = $staff; }
}
