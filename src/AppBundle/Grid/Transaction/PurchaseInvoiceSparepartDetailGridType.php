<?php

namespace AppBundle\Grid\Transaction;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectRepository;
use LibBundle\Grid\DataGridType;
use LibBundle\Grid\WidgetsBuilder;
use LibBundle\Grid\DataBuilder;
use LibBundle\Grid\SortOperator\BlankType as SortBlankType;
use LibBundle\Grid\SortOperator\AscendingType;
use LibBundle\Grid\SortOperator\DescendingType;
use LibBundle\Grid\SearchOperator\EqualNonEmptyType;
use LibBundle\Grid\SearchOperator\BlankType as SearchBlankType;
use LibBundle\Grid\SearchOperator\EqualType;
use LibBundle\Grid\SearchOperator\ContainType;
use AppBundle\Entity\Transaction\PurchaseInvoiceSparepartDetail;

class PurchaseInvoiceSparepartDetailGridType extends DataGridType
{
    public function buildWidgets(WidgetsBuilder $builder, array $options)
    {
        $builder->searchWidget()
            ->addGroup('purchaseInvoiceSparepartDetail')
                ->setEntityName(PurchaseInvoiceSparepartDetail::class)
                ->addField('itemCode')
                    ->addOperator(EqualNonEmptyType::class)
                ->addField('itemName')
                    ->addOperator(EqualNonEmptyType::class)
                ->addField('supplySysNumber')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                    ->addOperator(ContainType::class)
                ->addField('supplySlipNumber')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                    ->addOperator(ContainType::class)
                ->addField('quantity')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('itemPrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('servicePrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('materialPrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('sparepartPrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('outsourcePrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('oilPrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('accPrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('packagePrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('souvenirPrice')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('serviceDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('materialDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('sparepartDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('outsourceDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('oilDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('accessoryDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('packageDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('souvenirDiscount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('totalBeforeTax')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('totalTax')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('totalAfterTax')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
                ->addField('purchaseAmount')
                    ->addOperator(SearchBlankType::class)
                    ->addOperator(EqualType::class)
        ;

        $builder->sortWidget()
            ->addGroup('purchaseInvoiceSparepartDetail')
                ->addField('itemCode')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('itemName')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('supplySysNumber')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('supplySlipNumber')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('quantity')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('itemPrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('servicePrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('materialPrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('sparepartPrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('outsourcePrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('oilPrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('accPrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('packagePrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('souvenirPrice')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('serviceDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('materialDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('sparepartDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('outsourceDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('oilDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('accessoryDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('packageDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('souvenirDiscount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('totalBeforeTax')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('totalTax')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('totalAfterTax')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
                ->addField('purchaseAmount')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
        ;

        $builder->pageWidget()
            ->addPageSizeField()
                ->addItems(10, 25, 50, 100)
            ->addPageNumField()
        ;
    }

    public function buildData(DataBuilder $builder, ObjectRepository $repository, array $options)
    {
        $criteria = Criteria::create();

        $builder->processSearch(function($values, $operator, $field) use ($criteria) {
            $operator::search($criteria, $field, $values);
        });

        $builder->processSort(function($operator, $field) use ($criteria) {
            $operator::sort($criteria, $field);
        });

        $builder->processPage($repository->count($criteria), function($offset, $size) use ($criteria) {
            $criteria->setMaxResults($size);
            $criteria->setFirstResult($offset);
        });
        
        $objects = $repository->match($criteria);

        $builder->setData($objects);
    }
}
