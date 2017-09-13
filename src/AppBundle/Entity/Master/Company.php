<?php

namespace AppBundle\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="master_company")
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"bank" = "AppBundle\Entity\Master\BankingCompany", "finance" = "AppBundle\Entity\Master\FinanceCompany"})
 * @UniqueEntity("name")
 */
abstract class Company
{
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    protected $isActive = true;
    
    public function __construct()
    {
    }
    
    public function getId() { return $this->id; }
    
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
    
    public function getIsActive() { return $this->isActive; }
    public function setIsActive($isActive) { $this->isActive = $isActive; }
}
