<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Transaction\PurchaseInvoiceSparepartDetail;

class PurchaseInvoiceSparepartDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('itemCode')
            ->add('itemName')
            ->add('supplySysNumber')
            ->add('supplySlipNumber')
            ->add('quantity')
            ->add('itemPrice')
            ->add('servicePrice')
            ->add('materialPrice')
            ->add('sparepartPrice')
            ->add('outsourcePrice')
            ->add('oilPrice')
            ->add('accPrice')
            ->add('packagePrice')
            ->add('souvenirPrice')
            ->add('serviceDiscount')
            ->add('materialDiscount')
            ->add('sparepartDiscount')
            ->add('outsourceDiscount')
            ->add('oilDiscount')
            ->add('accessoryDiscount')
            ->add('packageDiscount')
            ->add('souvenirDiscount')
            ->add('totalBeforeTax')
            ->add('totalTax')
            ->add('totalAfterTax')
            ->add('purchaseAmount')
            ->add('purchaseInvoiceSparepartHeader')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PurchaseInvoiceSparepartDetail::class,
        ));
    }
}
