<?php

namespace AppBundle\Service\Report;

use LibBundle\Grid\DataGridView;
use AppBundle\Repository\Report\JournalLedgerRepository;

class AccountJournalLedgerSummary
{
    private $journalLedgerRepository;
    
    public function __construct(JournalLedgerRepository $journalLedgerRepository)
    {
        $this->journalLedgerRepository = $journalLedgerRepository;
    }
    
    public function getBeginningBalance(DataGridView $dataGridView)
    {
        $startDate = $dataGridView->searchVals['journalLedger']['transactionDate'][1];
        $account = empty($dataGridView->data) ? null : $dataGridView->data[0]->getAccount();
        $beginningBalance = $this->journalLedgerRepository->getAccountBeginningBalance($account, $startDate);
        return $beginningBalance;
    }
}
