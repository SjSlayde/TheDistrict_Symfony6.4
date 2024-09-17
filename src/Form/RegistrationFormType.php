<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
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
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'class' => 'col-3 form-control'    
                        ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setRole(...))
            // ->add('Save', SubmitType::class, [
            //     'label' => 'Envoyer',
            //     'attr' => [
            //         'class' => 'btn btn-success color-315F72 rounded-pill col-1'
            //     ],
            //     'row_attr' => [
            //         'class' => 'd-flex justify-content-end'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }

    public function setRole(PostSubmitEvent $event):Void
    {
        $data = $event->getData();
        $data->setRoles(['ROLE_CLIENT']);
    }
}
