<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;

class PlatFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr' => [
                    'class' => 'col-3 form-control'
                ]
            ])
            ->add('prix',TextType::class,[
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
                        'mimeTypesMessage' => 'veuillez uploads une image (\'.png/jpg/jpeg/webp\') ',
                    ])]
                ])
            ->add('active')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'label' => 'categorie :'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Commander',
                'attr' => [
                    'class' => 'btn btn-success color-315F72 rounded-pill '
                ],
                'row_attr' => [
                    'class' => 'd-flex justify-content-end'
                ]
            ])
            // ->addEventListener(FormEvents::PRE_SUBMIT, $this->DownloadImage(...))
        ;
    }

    // public function DownloadImage(PreSubmitEvent $event){
    //     $data = $event->getData();
    //     if (empty($data['image'])){
    //         //$cheminimage = uniqid() . '_' . $data['image']['name'];
   
    //         $destination = '../../assets/img/food/' . $data['image']['name'];
        
    //         // Déplacement du fichier uploadé
        
    //         if (move_uploaded_file($data['image']['tmp_name'], $destination)) {
        
    //             echo 'Fichier uploadé avec succès !';
        
    //         } else {
        
    //             echo 'Erreur lors de l\'upload du fichier.';
        
    //         }
    //         // $someNewFilename = ...
    //         var_dump($data);
            
    //         $data['image']->move($destination, $data['image']['name']);
    
    //         $data['image'] = $data['image']['name'];
    //         $event->setData($data);
    //     }
    // }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}