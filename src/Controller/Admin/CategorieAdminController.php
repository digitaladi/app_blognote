<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\AddCategorieFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categorie', name: 'app_admin_categorie_')]
class CategorieAdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategorieRepository $categorieRepository): Response
    {

        $categories = $categorieRepository->findAll();
        //dd($categories);
        return $this->render('admin/categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    #[Route('/addCategorie', name: 'add')]
    public function addCategorie(Request $request, SluggerInterface $slugger, EntityManagerInterface $em ): Response
    {

            $categorie = new Categorie();
            $formCategorie = $this->createForm(AddCategorieFormType::class, $categorie);

            $formCategorie->handleRequest($request);
           

            if($formCategorie->isSubmitted() && $formCategorie->isValid()){
                
                //dd($formCategorie);

                $categorie->setSlug( strtolower($slugger->slug($categorie->getName())));
                
                
                $em->persist($categorie);
                $em->flush();

                $this->addFlash('success', 'La catégorie  a été ajoutée');
                return $this->redirectToRoute('app_admin_categorie_index');

                
            }




        return $this->render('admin/categorie/add.html.twig', [
            'formCategorie' => $formCategorie,
        ]);
    }






    #[Route('/edit/{id}', name:"edit", methods: ['GET', 'POST'])]
    public function Edit(EntityManagerInterface $em, Categorie $categorie,Request $request, SluggerInterface $slugger) : Response{
    


            $categorieFormAdmin = $this->createForm(AddCategorieFormType::class, $categorie);

                 //on traite le formualaire
             $categorieFormAdmin->handleRequest($request);

            if($categorieFormAdmin->isSubmitted() && $categorieFormAdmin->isValid()){

                $slug = strtolower($slugger->slug($categorie->getName()) );

         




                //dd($slug);
                //on assgine uen valeur au slug de  l'astuce
                $categorie->setSlug($slug);
        
                $em->persist($categorie);
                $em->flush();

                $this->addFlash('success', 'La catégorie a été modifiée');
                return $this->redirectToRoute('app_admin_categorie_index');
            }
            return $this->render('admin/categorie/edit.html.twig', [
                    'categorieFormAdmin' => $categorieFormAdmin,
                ]);
        }




        #[Route('/show/{id}', name:"show")]
        public function showTrickAdmin(Categorie $categorie): Response{
            
          // dd($categorie);
    
           if(!$categorie){
            throw $this->createNotFoundException("Cette categorie n'existe pas ");
           }
            return $this->render('admin/categorie/show.html.twig', [
                'categorie' => $categorie,
            ]);
        }


        #[Route('/delete/{id}', name:"delete")]
        public function deleteTrickAdmin(Categorie $categorie, EntityManagerInterface $em):Response{


            if($categorie){
                $em->remove($categorie);
                $em->flush();
                $this->addFlash('success', 'La catégorie a été supprimée');
                return $this->redirectToRoute('home_admin_categorie_index');
            }else{
                $this->addFlash('warning', 'La catégorie n a pas été trouvé');
            }


    }


}
