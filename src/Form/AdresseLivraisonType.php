<?php

namespace App\Form;

use App\Entity\AdresseLivraison;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

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
            ->add('agreeTerms', CheckboxType::class, [
                    'mapped' => false,
                    'label' => 'Veuillez accepter les conditions générales d\'utilisation',
                    'constraints' => [
                        new IsTrue([
                            'message' => 'Veuillez accepter les termes d\'utilisation ',
                        ]),
                ],
                ])
            ->add('save', SubmitType::class, [
                'label' => 'Suivant',
                'attr' => [
                    'class' => 'btn btn-success color-315F72 rounded-pill '
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
            'data_class' => AdresseLivraison::class,
        ]);
    }
}
