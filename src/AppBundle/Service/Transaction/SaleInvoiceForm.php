<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SaleInvoice;
use AppBundle\Repository\Transaction\SaleInvoiceRepository;

class SaleInvoiceForm
{
    private $saleInvoiceRepository;
    
    public function __construct(SaleInvoiceRepository $saleInvoiceRepository)
    {
        $this->saleInvoiceRepository = $saleInvoiceRepository;
    }
    
    public function initialize(SaleInvoice $saleInvoice, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($saleInvoice->getId())) {
            $lastSaleInvoice = $this->saleInvoiceRepository->findRecentBy($year, $month);
            $currentSaleInvoice = ($lastSaleInvoice === null) ? $saleInvoice : $lastSaleInvoice;
            $saleInvoice->setCodeNumberToNext($currentSaleInvoice->getCodeNumber(), $year, $month);
            
            $saleInvoice->setStaffFirst($staff);
        }
        $saleInvoice->setStaffLast($staff);
    }
    
    public function finalize(SaleInvoice $saleInvoice, array $params = array())
    {
        $this->sync($saleInvoice);
    }
    
    private function sync(SaleInvoice $saleInvoice)
    {
        $saleInvoice->sync();
    }
    
    public function save(SaleInvoice $saleInvoice)
    {
        if (empty($saleInvoice->getId())) {
            $this->saleInvoiceRepository->add($saleInvoice);
        } else {
            $this->saleInvoiceRepository->update($saleInvoice);
        }
    }
    
    public function delete(SaleInvoice $saleInvoice)
    {
        $this->beforeDelete($saleInvoice);
        if (!empty($saleInvoice->getId())) {
            $this->saleInvoiceRepository->remove($saleInvoice);
        }
    }
    
    protected function beforeDelete(SaleInvoice $saleInvoice)
    {
        $this->sync($saleInvoice);
    }
}
