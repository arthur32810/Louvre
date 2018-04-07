<?php

namespace Louvre\TicketingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Louvre\TicketingBundle\Form\BilletType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('day', DateType::class, array('label' => 'Jour de la visite',
            'widget' => 'single_text',
            'html5' => true,
            'attr' => ['class' => 'data-picker']))
        ->add('billet',     CollectionType::class, array(
            'entry_type'    => BilletType::class,
            'allow_add'     => true,
            'allow_delete'  => true,
            'by_reference'  => false
        ))
        ->add('Commander',   SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketingBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketingbundle_reservation';
    }


}
