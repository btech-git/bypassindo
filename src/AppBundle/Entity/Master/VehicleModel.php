<?php

namespace AppBundle\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="master_vehicle_model") @ORM\Entity
 * @UniqueEntity("manufactureCode")
 */
class VehicleModel
{
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $manufactureCode;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $vldModelName;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $vosModelName;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $dmsVariantName;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $sundry;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     */
    private $description;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isActive = true;
    /**
     * @ORM\ManyToOne(targetEntity="VehicleModelType", inversedBy="vehicleModels")
     * @Assert\NotNull()
     */
    private $vehicleModelType;
    
    public function __construct()
    {
    }
    
    public function __toString()
    {
        return $this->manufactureCode;
    }
    
    public function getId() { return $this->id; }

    public function getManufactureCode() { return $this->manufactureCode; }
    public function setManufactureCode($manufactureCode) { $this->manufactureCode = $manufactureCode; }

    public function getVldModelName() { return $this->vldModelName; }
    public function setVldModelName($vldModelName) { $this->vldModelName = $vldModelName; }

    public function getVosModelName() { return $this->vosModelName; }
    public function setVosModelName($vosModelName) { $this->vosModelName = $vosModelName; }

    public function getDmsVariantName() { return $this->dmsVariantName; }
    public function setDmsVariantName($dmsVariantName) { $this->dmsVariantName = $dmsVariantName; }

    public function getSundry() { return $this->sundry; }
    public function setSundry($sundry) { $this->sundry = $sundry; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getIsActive() { return $this->isActive; }
    public function setIsActive($isActive) { $this->isActive = $isActive; }

    public function getVehicleModelType() { return $this->vehicleModelType; }
    public function setVehicleModelType(VehicleModelType $vehicleModelType = null) { $this->vehicleModelType = $vehicleModelType; }
}
