<?php

// 
namespace App\Controller;

use App\Entity\Trick;
use App\Form\GetTrickByCategorieFormType;
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



        $formTrcikByCategorie = $this->createForm(GetTrickByCategorieFormType::class);
       // dd($formTrcikByCategorie);
      $trickByCategory = $trickRepository->findAll();
       $categories =   $categorieRepository->tricksByCategory();
      // $tricks  = $trickRepository->trickByCategory();
      // dd($trickByCategory);


        $tabsfinals = [];

        foreach ($categories as $key => $value) {
            $tabsfinals[$value['name']] = $value['tricks'];
            //dd($value['name'] = $value['tricks']);
        }


     //  dd($tabsfinals);
     // 

        
        //$tab = [ "kevin", "joe", "rambo"];
        return $this->render('main/index.html.twig', [
            'tricks' => $trickByCategory,
            'categories' => $categories,
            'is_actif' => true,
            'formTrcikByCategorie' =>$formTrcikByCategorie
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
   // $this->denyAccessUnlessGranted('ROLE_USER');

return $this->render('main/trickday.html.twig');
}



#[Route('main/trick/show/{id}', name:"main_show_trick")]
/**
 * Afficher une astuce (coté profil)
 *
 * @param Trick $trick
 * @return Response
 */
public function show(Trick $trick): Response{
    
   dd($trick);

   if(!$trick){
    throw $this->createNotFoundException("Cette astuce n'existe pas ");
   }
    return $this->render('profil/trick/show.html.twig', [
        'trick' => $trick,
    ]);
}
















}




