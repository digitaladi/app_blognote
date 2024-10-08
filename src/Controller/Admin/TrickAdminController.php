<?php


namespace App\Controller\Admin;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrickAdminController  extends AbstractController{
    // 


#[Route('/admin/tricks', name:"home_admin_trick")]
    public function getTricks( TrickRepository $trickRepository ) : Response{

       $tricks =  $trickRepository->findAll();
        //dd($tricks);
        return $this->render('main/index.html.twig', [
            'tricks' => $tricks,
            //'is_actif' => true
        ]);
    }

    
}