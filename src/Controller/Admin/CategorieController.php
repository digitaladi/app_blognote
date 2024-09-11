<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\AddCategorieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categorie', name: 'app_admin_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/categorie/index.html.twig', [
            'controller_name' => 'Je suis la page index de catergorie',
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

}
