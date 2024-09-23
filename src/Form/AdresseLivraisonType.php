<?php

namespace App\Form;

use App\Entity\AdresseLivraison;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseLivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('prenom', TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('telephone', TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('adresse', TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('cp', TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('ville',TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
//             ->add('utilisateur', EntityType::class, [
//                 'class' => Utilisateur::class,
// 'choice_label' => 'id',
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdresseLivraison::class,
        ]);
    }
}
