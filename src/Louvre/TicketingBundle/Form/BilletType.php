<?php

namespace Louvre\TicketingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',    TextType::class, array('label' => 'Prénom'))
        ->add('lastname',       TextType::class, array('label' => 'Nom'))
        ->add('birthday',   BirthdayType::class, array('label' => 'Date de naissance',
            'widget' => 'single_text',
            'data' => new \DateTime(),
            'html5' => true,
            'attr' => ['class' => 'data-picker']))
        ->add('country',    CountryType::class, array('placeholder' => 'Sélectionner votre pays', 'label' => 'Pays'))
        ->add('duration',     ChoiceType::class, array('label' => 'Ticket',
            'choices' => array(
                'Journée' => 1,
                'Demi-Journée (à partir de 14h)' => 0.5),
         'placeholder' => 'Choisissez votre type de billet'))
        ->add('reduction',  CheckboxType::class, array('required' => false, 'label' => 'Tarif réduit (étudiant, employé du musée, d’un service du Ministère de la Culture, militaire)'));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketingBundle\Entity\Billet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketingbundle_billet';
    }


}
