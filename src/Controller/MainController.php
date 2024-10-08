<?php

// 
namespace App\Controller;

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
    public function index(TrickRepository $trickRepository, EntityManagerInterface $entityManagerInterface): Response
    {




        $tricks  = $trickRepository->trickByCategory();
        //dd($tricks);

        // 

        
        //$tab = [ "kevin", "joe", "rambo"];
        return $this->render('main/index.html.twig', [
            'tricks' => $tricks,
            'is_actif' => true
        ]);
    }




#[Route('/mentions', name: 'app_mentions')]
public function  mentions():Response
{
    return $this->render("main/mentions.html.twig");
}

}
