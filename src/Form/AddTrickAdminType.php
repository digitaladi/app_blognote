<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Keyword;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
class AddTrickAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'astuce',
                'label_attr' =>[
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => 'form-control'
                ]

            ])
         
            ->add('content', TextareaType::class, [
                'label' => 'Le descriptif de l\'astuce',
                'label_attr' =>[
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('featureimage', FileType::class, [

               'label' => 'Image d\'illustration',
               'label_attr' =>[
                'class' => 'form-label mt-4'
            ],
               'mapped' => false,
              // 'multiple' => true,
              'attr'=>[
                'accept' => 'image/png, image/jpeg, image/webp',
                 'class' => 'form-control'
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
                'choice_attr' => function () { return array('class' => 'ms-2'); },//styliser une option
                'multiple' => true, //on peut choisir plusieurs
                 'expanded' => true, // case à cocher
                 'label' => 'La catégorie de l\'astuce',
                 'label_attr' =>[
                     'class' => 'form-label mt-4'
                 ],
   
                 'attr' => [
                    'class' => ''
                ]
            ])
            ->add('keyword', EntityType::class, [
                'label' => 'Mot clé de l\'astuce',
                'label_attr' =>[
                    'class' => 'form-label mt-4'
                ],
                'choice_attr' => function () { return array('class' => 'ms-2'); },//styliser une option
  
                'attr' => [
                   'class' => ''
                ],
                'class' => Keyword::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, // case à cocher
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
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
