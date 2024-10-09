<?php

namespace App\Form;

use App\Entity\MoyenPaiement;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MoyenPaimentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    $builder
        ->add('numeros_de_carte', TextType::class, [
            'label'=> 'numéros de carte',
            'attr' => [ 'class' => 'col-3 form-control'    
                    ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer vos numéros de carte bancaire(promis je ne l\'est utilise pas)',
                ]),
                new Length([
                    'min' => 16,
                    'minMessage' => 'minimum {{ limit }} characters',
                    'max' => 16,
                ])]])

                
        ->add('expiration', TextType::class, [
            'label'=> 'date d\'expiration de la carte bancaire',
            'attr' => ['class' => 'col-3 form-control'    
                    ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer la date expiration de votre carte bancaire',
                ]),
                new Length([
                    'min' => 4,
                    'minMessage' => 'minimum {{ limit }} characters',
                    'max' => 4096,
                ])]])

                
        ->add('code_securite', TextType::class, [
            'label'=> 'code de securité',
            'attr' => ['class' => 'col-3 form-control'    
                    ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer le code de securité de la carte bancaire',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'minimum {{ limit }} characters',
                    'max' => 3,
                ])]])

                
        ->add('nom_titulaire', TextType::class, [
            'label'=> 'nom du titulaire',
            'attr' => ['class' => 'col-3 form-control'    
                    ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer le nom et prenom du titulaire de la carte bancaire',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'minimum {{ limit }} characters',
                    'max' => 4096,
                ])]
                ])
                // ->add('save', SubmitType::class, [
                //     'label' => 'Commander',
                //     'attr' => [
                //         'class' => 'btn btn-success color-315F72 rounded-pill '
                //     ],
                //     'row_attr' => [
                //         'class' => 'd-flex justify-content-end'
                //     ]
                //     ]);

    //             ->add('utilisateur', EntityType::class, [
    //                 'class' => Utilisateur::class,
    // 'choice_label' => 'id',
    //             ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MoyenPaiement::class,
        ]);
    }
}
