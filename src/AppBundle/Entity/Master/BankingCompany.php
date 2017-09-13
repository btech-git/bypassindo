<?php

namespace AppBundle\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 */
class BankingCompany extends Company
{
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $accountNumber;
    
    public function __toString()
    {
        return $this->name;
    }
    
    public function getAccountNumber() { return $this->accountNumber; }
    public function setAccountNumber($accountNumber) { $this->accountNumber = $accountNumber; }
}
