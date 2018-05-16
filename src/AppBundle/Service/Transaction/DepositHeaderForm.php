<?php

namespace AppBundle\Service\Transaction;

use LibBundle\Doctrine\ObjectPersister;
use AppBundle\Entity\Transaction\DepositHeader;
use AppBundle\Entity\Report\JournalLedger;
use AppBundle\Repository\Transaction\DepositHeaderRepository;
//use AppBundle\Repository\Report\JournalLedgerRepository;

class DepositHeaderForm
{
    private $depositHeaderRepository;
//    private $journalLedgerRepository;
    
    public function __construct(DepositHeaderRepository $depositHeaderRepository)
    {
        $this->depositHeaderRepository = $depositHeaderRepository;
//        $this->journalLedgerRepository = $journalLedgerRepository;
    }
    
    public function initialize(DepositHeader $depositHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($depositHeader->getId())) {
            $lastDepositHeader = $this->depositHeaderRepository->findRecentBy($year, $month);
            $currentDepositHeader = ($lastDepositHeader === null) ? $depositHeader : $lastDepositHeader;
            $depositHeader->setCodeNumberToNext($currentDepositHeader->getCodeNumber(), $year, $month);
            
            $depositHeader->setStaff($staff);
        }
    }
    
    public function finalize(DepositHeader $depositHeader, array $params = array())
    {
        foreach ($depositHeader->getDepositDetails() as $depositDetail) {
            $depositDetail->setDepositHeader($depositHeader);
        }
    }
    
    public function save(DepositHeader $depositHeader)
    {
        if (empty($depositHeader->getId())) {
            ObjectPersister::save(function() use ($depositHeader) {
                $this->depositHeaderRepository->add($depositHeader, array(
                    'depositDetails' => array('add' => true),
                ));
//                $this->markJournalLedgers($depositHeader, true);
            });
        } else {
            ObjectPersister::save(function() use ($depositHeader) {
                $this->depositHeaderRepository->update($depositHeader, array(
                    'depositDetails' => array('add' => true, 'remove' => true),
                ));
//                $this->markJournalLedgers($depositHeader, true);
            });
        }
    }
    
    public function delete(DepositHeader $depositHeader)
    {
        $this->beforeDelete($depositHeader);
        if (!empty($depositHeader->getId())) {
            ObjectPersister::save(function() use ($depositHeader) {
                $this->depositHeaderRepository->remove($depositHeader, array(
                    'depositDetails' => array('remove' => true),
                ));
//                $this->markJournalLedgers($depositHeader, false);
            });
        }
    }
    
    protected function beforeDelete(DepositHeader $depositHeader)
    {
        $depositHeader->getDepositDetails()->clear();
        $this->sync($depositHeader);
    }
    
    private function markJournalLedgers(DepositHeader $depositHeader, $addForHeader)
    {
        $oldJournalLedgers = $this->journalLedgerRepository->findBy(array(
            'transactionType' => JournalLedger::TRANSACTION_TYPE_DEPOSIT,
            'codeNumberYear' => $depositHeader->getCodeNumberYear(),
            'codeNumberMonth' => $depositHeader->getCodeNumberMonth(),
            'codeNumberOrdinal' => $depositHeader->getCodeNumberOrdinal(),
        ));
        $this->journalLedgerRepository->remove($oldJournalLedgers);
        foreach ($depositHeader->getDepositDetails() as $depositDetail) {
            if ($depositDetail->getAmount() > 0) {
                $journalLedger = new JournalLedger();
                $journalLedger->setCodeNumber($depositHeader->getCodeNumber());
                $journalLedger->setTransactionDate($depositHeader->getTransactionDate());
                $journalLedger->setTransactionType(JournalLedger::TRANSACTION_TYPE_DEPOSIT);
                $journalLedger->setTransactionSubject($depositDetail->getMemo());
                $journalLedger->setNote($depositHeader->getNote());
                $journalLedger->setDebit(0);
                $journalLedger->setCredit($depositDetail->getAmount());
                $journalLedger->setAccount($depositDetail->getAccount());
                $journalLedger->setStaff($depositHeader->getStaff());
                $this->journalLedgerRepository->add($journalLedger);
            }
        }
        if ($addForHeader) {
            $journalLedger = new JournalLedger();
            $journalLedger->setCodeNumber($depositHeader->getCodeNumber());
            $journalLedger->setTransactionDate($depositHeader->getTransactionDate());
            $journalLedger->setTransactionType(JournalLedger::TRANSACTION_TYPE_DEPOSIT);
            $journalLedger->setTransactionSubject('Deposit');
            $journalLedger->setNote($depositHeader->getNote());
            $journalLedger->setDebit($depositHeader->getTotalAmount());
            $journalLedger->setCredit(0);
            $journalLedger->setAccount($depositHeader->getAccount());
            $journalLedger->setStaff($depositHeader->getStaff());
            $this->journalLedgerRepository->add($journalLedger);
        }
    }
}
