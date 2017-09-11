<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use LibBundle\Form\Type\EntityTextType;
use AppBundle\Entity\Transaction\SaleInvoice;
use AppBundle\Entity\Transaction\ReceiveOrder;

class SaleInvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', 'date')
            ->add('taxNumberPrefix')
            ->add('taxNumber')
            ->add('note')
            ->add('receiveOrder', EntityTextType::class, array('class' => ReceiveOrder::class))
        ;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $saleInvoice = $event->getData();
                $options['service']->initialize($saleInvoice, $options['init']);
            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($options) {
                $saleInvoice = $event->getData();
                $options['service']->finalize($saleInvoice);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SaleInvoice::class,
        ));
        $resolver->setRequired(array('service', 'init'));
    }
}
