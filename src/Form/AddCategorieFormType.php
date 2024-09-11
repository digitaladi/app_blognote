<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Trick;
use App\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCategorieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la catégorie '
            ])

            ->add('parent', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name', //un attribut choisi dans Categorie
                'label' => 'Donnez un parent ',
                'placeholder' =>  '-- pas de parent --', //affichage null
                'required' => false ,// non obligatoire,

                //query_builder permet de créer une requete autour d'une champs de type entitytype
                //ex ici :  avec l'aide CategorieRepository on affiche les catégories par ordre ascendat
                'query_builder' => function(CategorieRepository $cr){

                    return $cr->createQueryBuilder('c')
                    ->orderBy("c.name", 'ASC');

                }
            ])

            /*
            ->add('tricks', EntityType::class, [
                'class' => Trick::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
