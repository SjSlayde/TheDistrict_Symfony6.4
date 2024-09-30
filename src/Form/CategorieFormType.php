<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CategorieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('image',FileType::class,[
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'col-3 form-control'
                ],
                'constraints' => [
                    new Image([
                        'maxSize' => '2048k',
                        'mimeTypesMessage' => 'veuillez uploads une image (\'.png/jpg/jpeg/webp\')',
                    ])]
                ])
            ->add('active')
            ->add('save', SubmitType::class, [
                'label' => 'Commander',
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
            'data_class' => Categorie::class,
        ]);
    }
}
