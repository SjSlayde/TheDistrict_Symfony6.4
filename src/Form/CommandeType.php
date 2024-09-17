<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class,[
            'attr' => [
                'class' => 'col-3 form-control'
            ]
        ])
        ->add('prenom',TextType::class,[
            'attr' => [
                'class' => 'col-3 form-control'
            ]
        ])
        ->add('telephone',TextType::class,[
            'attr' => [
                'class' => 'col-3 form-control'
            ]
        ])
        ->add('adresse',TextType::class,[
            'attr' => [
                'class' => 'col-3 form-control'
            ]
        ])
        ->add('cp',TextType::class,[
            'attr' => [
                'class' => 'col-3 form-control'
            ]
        ])
        ->add('ville',TextType::class,[
            'attr' => [
                'class' => 'col-3 form-control'
            ]
        ])
        ->add('email',EmailType::class,[
            'attr' => [
                'class' => 'col-3 form-control'
            ]
        ])
        ->add('Save', SubmitType::class, [
            'label' => 'Envoyer',
            'attr' => [
                'class' => 'btn btn-success color-315F72 rounded-pill'
            ],
            'row_attr' => [
                'class' => 'd-flex justify-content-end'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
