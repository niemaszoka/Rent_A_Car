<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class RentForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $resolver = new OptionsResolver();

        $this->configureOptions($resolver);

        $builder
        ->add('carId', HiddenType::class)
        ->add('pricePerDay', HiddenType::class)
        ->add('email', EmailType::class)
        ->add('firstName', TextType::class)
        ->add('lastName', TextType::class)
        ->add('confirm', SubmitType::class)
        ->add('rentDays', NumberType::class)
        ->getForm();


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CarRental'
        ));
    }
}