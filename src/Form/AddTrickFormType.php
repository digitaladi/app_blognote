<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Keyword;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'astuce',
                'constraints' => [
                    new NotBlank()
                ]

            ])
         
            ->add('content', TextareaType::class, [
                'label' => 'Le descriptif de l\'astuce'
            ])
            ->add('featureimage', FileType::class, [

               'label' => 'Image d\'illustration',
               'mapped' => false,
              // 'multiple' => true,
              'attr'=>[
                'accept' => 'image/png, image/jpeg, image/webp'
              ],
               'constraints' => [
            //    new All( //permet de mettre des contraintes sur plusieurs 
                  
                    new Image(
                        minWidth: 200,
                        maxWidth: 4000,
                        minHeight: 200,
                        maxHeight: 4000,
                        allowPortrait: true,
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ]
                    )

        //    ),

            ]
            ])

/*
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])

*/
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true, //on peut choisir plusieurs
                 'expanded' => true, // case à cocher
            ])
            ->add('keyword', EntityType::class, [
                'class' => Keyword::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, // case à cocher
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
