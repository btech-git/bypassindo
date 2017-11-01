<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Transaction\PurchaseInvoiceSparepartHeader;

class PurchaseInvoiceSparepartHeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoiceNumber')
            ->add('workOrderNumber')
            ->add('transactionDate')
            ->add('companyName')
            ->add('taxNumber')
            ->add('prmCpc')
            ->add('prmTrx')
            ->add('customerCode')
            ->add('customerName')
            ->add('vehicleModel')
            ->add('vehicleChassisNumber')
            ->add('vehicleEngineNumber')
            ->add('vehicleLicenseNumber')
            ->add('vehicleClaimNumber')
            ->add('vehiclePolicyNumber')
            ->add('totalPackage')
            ->add('totalOpr')
            ->add('totalPart')
            ->add('totalOil')
            ->add('totalMaterial')
            ->add('totalAccessory')
            ->add('totalSublet')
            ->add('totalSouvenir')
            ->add('discountPackage')
            ->add('discountOpr')
            ->add('discountPart')
            ->add('discountOil')
            ->add('discountMaterial')
            ->add('discountAccessory')
            ->add('discountSublet')
            ->add('discountSouvenir')
            ->add('grandTotalBeforeTax')
            ->add('grandTotalTax')
            ->add('grandTotalAfterTax')
            ->add('totalPurchaseAmount')
            ->add('paymentType')
            ->add('cpcDescription')
            ->add('trxDescription')
            ->add('taxInvoiceDocumentNumber')
            ->add('productionYear')
            ->add('serviceMileage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PurchaseInvoiceSparepartHeader::class,
        ));
    }
}
