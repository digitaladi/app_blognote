<?php

// 
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{


    /**
     * Undocumented function
     *
     * @param TrickRepository $trickRepository
     * @param EntityManagerInterface $entityManagerInterface
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $trickRepository, CategorieRepository $categorieRepository,  EntityManagerInterface $entityManagerInterface): Response
    {


    
       // $trickByCategory = $trickRepository->getTr
       $categories =   $categorieRepository->tricksByCategory();
     //   $tricks  = $trickRepository->trickByCategory();
      //  dd($categories);

        // 

        
        //$tab = [ "kevin", "joe", "rambo"];
        return $this->render('main/index.html.twig', [
            'categories' => $categories,
            'is_actif' => true
        ]);
    }




#[Route('/mentions', name: 'app_mentions')]
public function  mentions():Response
{
    return $this->render("main/mentions.html.twig");
}



#[Route('/apropos', name: 'app_apropos')]

    public function apropos(): Response{
        return $this->render("main/apropos.html.twig");
}



#[Route('/trickday', name: 'trickday')]
public function TrickOfTheDay(): Response{
    $this->denyAccessUnlessGranted('ROLE_USER');

return $this->render('main/trickday.html.twig');
}


}