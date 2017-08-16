<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use LibBundle\Form\Type\EntityTextType;
use LibBundle\Util\ConstantValueList;
use AppBundle\Entity\Transaction\SaleDiscountApplication;
use AppBundle\Entity\Master\Customer;

class SaleDiscountApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', 'date')
            ->add('dealStatus', ChoiceType::class, array(
                'expanded' => true,
                'choices' => ConstantValueList::get(SaleDiscountApplication::class, 'DEAL_STATUS'),
                'choices_as_values' => true,
            ))
            ->add('dealDate', 'date')
            ->add('customerStatusType', ChoiceType::class, array(
                'expanded' => true,
                'choices' => ConstantValueList::get(SaleDiscountApplication::class, 'CUSTOMER_STATUS'),
                'choices_as_values' => true,
            ))
            ->add('customerStatusName')
            ->add('requestType')
            ->add('requestQuantity')
            ->add('requestUsageType')
            ->add('requestWorkshop')
            ->add('competitorBrand')
            ->add('competitorType')
            ->add('competitorDealer')
            ->add('paymentMethodType', ChoiceType::class, array(
                'expanded' => true,
                'choices' => ConstantValueList::get(SaleDiscountApplication::class, 'PAYMENT_METHOD'),
                'choices_as_values' => true,
            ))
            ->add('paymentMethodValue')
            ->add('customerPrice')
            ->add('salesmanPrice')
            ->add('approvedPrice')
            ->add('competitorPrice')
            ->add('offTheRoadPrice')
            ->add('registrationPrice')
            ->add('otherPrice')
            ->add('mediatorPrice')
            ->add('note')
            ->add('customer', EntityTextType::class, array('class' => Customer::class))
        ;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $saleDiscountApplication = $event->getData();
                $options['service']->initialize($saleDiscountApplication, $options['init']);
            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($options) {
                $saleDiscountApplication = $event->getData();
                $options['service']->finalize($saleDiscountApplication);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SaleDiscountApplication::class,
        ));
        $resolver->setRequired(array('service', 'init'));
    }
}
