<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        
        $tab = [ "kevin", "joe", "rambo"];
        return $this->render('main/index.html.twig', [
            'prenoms' => $tab,
            'is_actif' => true
        ]);
    }




#[Route('/mentions', name: 'app_mentions')]
public function  mentions():Response
{
    return $this->render("main/mentions.html.twig");
}

}
