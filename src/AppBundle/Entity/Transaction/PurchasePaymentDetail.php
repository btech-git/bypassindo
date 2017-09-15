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
 * @ORM\Table(name="transaction_purchase_payment_detail")
 * @ORM\Entity
 */
class PurchasePaymentDetail
{
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $amount;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     */
    private $memo;
    /**
     * @ORM\ManyToOne(targetEntity="PurchasePaymentHeader", inversedBy="purchasePaymentDetails")
     * @Assert\NotNull()
     */
    private $purchasePaymentHeader;
    
    public function __construct()
    {
    }
    
    public function getId() { return $this->id; }

    public function getAmount() { return $this->amount; }
    public function setAmount($amount) { $this->amount = $amount; }

    public function getMemo() { return $this->memo; }
    public function setMemo($memo) { $this->memo = $memo; }

    public function getPurchasePaymentHeader() { return $this->purchasePaymentHeader; }
    public function setPurchasePaymentHeader(PurchasePaymentHeader $purchasePaymentHeader = null) { $this->purchasePaymentHeader = $purchasePaymentHeader; }
}
