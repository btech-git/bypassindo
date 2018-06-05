<?php

namespace AppBundle\Service\Transaction;

use LibBundle\Doctrine\ObjectPersister;
use AppBundle\Entity\Transaction\ExpenseHeader;
use AppBundle\Entity\Report\JournalLedger;
use AppBundle\Repository\Transaction\ExpenseHeaderRepository;
//use AppBundle\Repository\Report\JournalLedgerRepository;

class ExpenseHeaderForm
{
    private $expenseHeaderRepository;
//    private $journalLedgerRepository;
    
    public function __construct(ExpenseHeaderRepository $expenseHeaderRepository)//, JournalLedgerRepository $journalLedgerRepository)
    {
        $this->expenseHeaderRepository = $expenseHeaderRepository;
//        $this->journalLedgerRepository = $journalLedgerRepository;
    }
    
    public function initialize(ExpenseHeader $expenseHeader, array $params = array())
    {
        list($staff) = array($params['staff']);
        
        if (empty($expenseHeader->getId())) {
            $expenseHeader->setStaff($staff);
        }
    }
    
    public function finalize(ExpenseHeader $expenseHeader, array $params = array())
    {
        if (empty($expenseHeader->getId())) {
            $transactionDate = $expenseHeader->getTransactionDate();
            if ($transactionDate !== null) {
                $month = intval($transactionDate->format('m'));
                $year = intval($transactionDate->format('y'));
                $lastExpenseHeaderApplication = $this->expenseHeaderRepository->findRecentBy($year, $month);
                $currentExpenseHeader = ($lastExpenseHeaderApplication === null) ? $expenseHeader : $lastExpenseHeaderApplication;
                $expenseHeader->setCodeNumberToNext($currentExpenseHeader->getCodeNumber(), $year, $month);
            }
        }
        foreach ($expenseHeader->getExpenseDetails() as $expenseDetail) {
            $expenseDetail->setExpenseHeader($expenseHeader);
        }
    }
    
    public function save(ExpenseHeader $expenseHeader)
    {
        if (empty($expenseHeader->getId())) {
            ObjectPersister::save(function() use ($expenseHeader) {
                $this->expenseHeaderRepository->add($expenseHeader, array(
                    'expenseDetails' => array('add' => true),
                ));
//                $this->markJournalLedgers($expenseHeader, true);
            });
        } else {
            ObjectPersister::save(function() use ($expenseHeader) {
                $this->expenseHeaderRepository->update($expenseHeader, array(
                    'expenseDetails' => array('add' => true, 'remove' => true),
                ));
//                $this->markJournalLedgers($expenseHeader, true);
            });
        }
    }
    
    public function delete(ExpenseHeader $expenseHeader)
    {
        $this->beforeDelete($expenseHeader);
        if (!empty($expenseHeader->getId())) {
            ObjectPersister::save(function() use ($expenseHeader) {
                $this->expenseHeaderRepository->remove($expenseHeader, array(
                    'expenseDetails' => array('remove' => true),
                ));
                $this->markJournalLedgers($expenseHeader, false);
            });
        }
    }
    
    protected function beforeDelete(ExpenseHeader $expenseHeader)
    {
        $expenseHeader->getExpenseDetails()->clear();
        $this->sync($expenseHeader);
    }
    
    private function markJournalLedgers(ExpenseHeader $expenseHeader, $addForHeader)
    {
        $oldJournalLedgers = $this->journalLedgerRepository->findBy(array(
            'transactionType' => JournalLedger::TRANSACTION_TYPE_EXPENSE,
            'codeNumberYear' => $expenseHeader->getCodeNumberYear(),
            'codeNumberMonth' => $expenseHeader->getCodeNumberMonth(),
            'codeNumberOrdinal' => $expenseHeader->getCodeNumberOrdinal(),
        ));
        $this->journalLedgerRepository->remove($oldJournalLedgers);
        foreach ($expenseHeader->getExpenseDetails() as $expenseDetail) {
            if ($expenseDetail->getAmount() > 0) {
                $journalLedger = new JournalLedger();
                $journalLedger->setCodeNumber($expenseHeader->getCodeNumber());
                $journalLedger->setTransactionDate($expenseHeader->getTransactionDate());
                $journalLedger->setTransactionType(JournalLedger::TRANSACTION_TYPE_EXPENSE);
                $journalLedger->setTransactionSubject($expenseDetail->getMemo());
                $journalLedger->setNote($expenseHeader->getNote());
                $journalLedger->setDebit($expenseDetail->getAmount());
                $journalLedger->setCredit(0);
                $journalLedger->setAccount($expenseDetail->getAccount());
                $journalLedger->setStaff($expenseHeader->getStaff());
                $this->journalLedgerRepository->add($journalLedger);
            }
        }
        if ($addForHeader) {
            $journalLedger = new JournalLedger();
            $journalLedger->setCodeNumber($expenseHeader->getCodeNumber());
            $journalLedger->setTransactionDate($expenseHeader->getTransactionDate());
            $journalLedger->setTransactionType(JournalLedger::TRANSACTION_TYPE_EXPENSE);
            $journalLedger->setTransactionSubject('Expense');
            $journalLedger->setNote($expenseHeader->getNote());
            $journalLedger->setDebit(0);
            $journalLedger->setCredit($expenseHeader->getTotalAmount());
            $journalLedger->setAccount($expenseHeader->getAccount());
            $journalLedger->setStaff($expenseHeader->getStaff());
            $this->journalLedgerRepository->add($journalLedger);
        }
    }
}
