<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use LibBundle\Form\Type\EntityTextType;
use AppBundle\Entity\Transaction\SalePayment;
use AppBundle\Entity\Transaction\SaleInvoice;
use AppBundle\Entity\Master\PaymentMethod;

class SalePaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', 'date')
            ->add('amount')
            ->add('note')
            ->add('paymentMethod', EntityTextType::class, array('class' => PaymentMethod::class))
            ->add('saleInvoice', EntityTextType::class, array('class' => SaleInvoice::class))
        ;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $salePayment = $event->getData();
                $options['service']->initialize($salePayment, $options['init']);
            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($options) {
                $salePayment = $event->getData();
                $options['service']->finalize($salePayment);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SalePayment::class,
        ));
        $resolver->setRequired(array('service', 'init'));
    }
}
