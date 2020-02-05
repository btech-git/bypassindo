<?php

namespace AppBundle\Grid\Report;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectRepository;
use LibBundle\Grid\DataGridType;
use LibBundle\Grid\WidgetsBuilder;
use LibBundle\Grid\DataBuilder;
use LibBundle\Grid\SortOperator\BlankType as SortBlankType;
use LibBundle\Grid\SortOperator\AscendingType;
use LibBundle\Grid\SortOperator\DescendingType;
use LibBundle\Grid\SearchOperator\BlankType as SearchBlankType;
use LibBundle\Grid\SearchOperator\EqualType;
use LibBundle\Grid\SearchOperator\BetweenType;
use LibBundle\Grid\SearchOperator\ContainType;
use LibBundle\Grid\Transformer\EntityTransformer;
use AppBundle\Entity\Master\Account;
use AppBundle\Entity\Report\JournalLedger;

class AccountJournalLedgerGridType extends DataGridType
{
    public function buildWidgets(WidgetsBuilder $builder, array $options)
    {
        $em = $options['em'];
        $accounts = $em->getRepository(Account::class)->findAll();
        $accountLabelModifier = function($account) { return $account->getName(); };
        
        $builder->searchWidget()
            ->addGroup('journalLedger')
                ->setEntityName(JournalLedger::class)
                ->addField('account')
                    ->setDataTransformer(new EntityTransformer($em, Account::class))
                    ->addOperator(EqualType::class)
                        ->getInput(1)
                            ->setListData($accounts, $accountLabelModifier)
                    ->setDefault(EqualType::class, $accounts[0])
                ->setEntityName(JournalLedger::class)
                ->addField('transactionDate')
                    ->addOperator(BetweenType::class)
                        ->getInput(1)
                            ->setAttributes(array('data-pick' => 'date'))
                        ->getInput(2)
                            ->setAttributes(array('data-pick' => 'date'))
                    ->setDefault(BetweenType::class, new \DateTime(), new \DateTime())
        ;

        $builder->sortWidget()
            ->addGroup('journalLedger')
                ->addField('name')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
        ;

        $builder->pageWidget()
            ->addPageSizeField()
                ->addItems(1000)
            ->addPageNumField()
        ;
    }

    public function buildData(DataBuilder $builder, ObjectRepository $repository, array $options)
    {
        list($criteria) = $this->getSpecifications($options);
        
        $criteria['journalLedger']->orderBy(array('transactionDate' => Criteria::ASC));

        $builder->processSearch(function($values, $operator, $field, $group) use ($criteria) {
            $operator::search($criteria[$group], $field, $values);
        });

        $builder->processSort(function($operator, $field, $group) use ($criteria) {
            $operator::sort($criteria[$group], $field);
        });

        $builder->processPage($repository->count($criteria['journalLedger']), function($offset, $size) use ($criteria) {
            $criteria['journalLedger']->setMaxResults($size);
            $criteria['journalLedger']->setFirstResult($offset);
        });
        
        $objects = $repository->match($criteria['journalLedger']);

        $builder->setData($objects);
    }

    private function getSpecifications(array $options)
    {
        $names = array('journalLedger');
        $criteria = array();
        foreach ($names as $name) {
            $criteria[$name] = Criteria::create();
        }

        return array($criteria);
    }
}
