<?php


namespace App\Controller\Admin;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainAdminController  extends AbstractController{
    // 


#[Route('/admin', name:"home_admin")]
    public function getTricks( TrickRepository $trickRepository ) : Response{

       $tricks =  $trickRepository->findAll();
        //dd($tricks);
        return $this->render('admin/index.html.twig', [
            'tricks' => $tricks,
            //'is_actif' => true
        ]);
    }

    
}