<?php

namespace AppBundle\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\MappedSuperclass
 */
abstract class CodeNumberAccountEntity
{
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\Range(min=1, max=9999)
     */
    private $codeNumberOrdinal;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\Range(min=1, max=12)
     */
    private $codeNumberMonth;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $codeNumberYear;

    abstract public function getCodeNumberConstant();

    abstract public function getCodeNumberAccountCode();
    
    public function getCodeNumberOrdinal() { return $this->codeNumberOrdinal; }
    public function setCodeNumberOrdinal($codeNumberOrdinal) { $this->codeNumberOrdinal = $codeNumberOrdinal; }
    
    public function getCodeNumberMonth() { return $this->codeNumberMonth; }
    public function setCodeNumberMonth($codeNumberMonth) { $this->codeNumberMonth = $codeNumberMonth; }
    
    public function getCodeNumberYear() { return $this->codeNumberYear; }
    public function setCodeNumberYear($codeNumberYear) { $this->codeNumberYear = $codeNumberYear; }

    public function getCodeNumber()
    {
        $numerals = self::makeRomanNumerals();
        
        return sprintf('%s.%04d/%s/%s/%02d', $this->getAccount()->getAlias(), intval($this->codeNumberOrdinal), $this->getCodeNumberConstant(), $numerals[intval($this->codeNumberMonth)], intval($this->codeNumberYear));
    }
    
    public function setCodeNumber($codeNumber)
    {
        $nums = array_flip(self::makeRomanNumerals());
        
        list($ordinal, , $month, $year, ) = explode('/', $codeNumber);
        
        $this->codeNumberOrdinal = intval($ordinal);
        $this->codeNumberMonth = $nums[$month];
        $this->codeNumberYear = intval($year);
    }
    
    public function setCodeNumberToNext($codeNumber, $currentYear, $currentMonth, $account)
    {
        $this->setCodeNumber($codeNumber);
        
        $cnMonth = intval($currentMonth);
        $cnYear = intval($currentYear);
        $ordinal = $this->codeNumberOrdinal;
        if ($cnMonth > $this->codeNumberMonth || $cnYear > $this->codeNumberYear || $account->getCode() !== $this->getCodeNumberAccountCode()) {
            $ordinal = 0;
        }
        
        $this->codeNumberOrdinal = $ordinal + 1;
        $this->codeNumberMonth = $cnMonth;
        $this->codeNumberYear = $cnYear;
    }
    
    private static function makeRomanNumerals()
    {
        return array('', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
    }
}
