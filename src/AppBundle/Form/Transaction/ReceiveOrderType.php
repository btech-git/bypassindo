<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use LibBundle\Form\Type\EntityTextType;
use AppBundle\Entity\Transaction\ReceiveOrder;
use AppBundle\Entity\Transaction\SaleOrder;

class ReceiveOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', 'date')
            ->add('vehicleProductionYear')
            ->add('vehicleChassisNumber')
            ->add('vehicleMachineNumber')
            ->add('vehicleDescription')
            ->add('serviceBookNumber')
            ->add('note')
            ->add('saleOrder', EntityTextType::class, array('class' => SaleOrder::class))
        ;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $deliveryOrder = $event->getData();
                $options['service']->initialize($deliveryOrder, $options['init']);
            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($options) {
                $deliveryOrder = $event->getData();
                $options['service']->finalize($deliveryOrder);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ReceiveOrder::class,
        ));
        $resolver->setRequired(array('service', 'init'));
    }
}
