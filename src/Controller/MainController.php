<?php

// 
namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Trick;
use App\Form\GetTrickByCategorieFormType;
use App\Form\RatingType;
use App\Repository\CategorieRepository;
use App\Repository\RatingRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
class MainController extends AbstractController
{

/*
    private function testCache($data){
      dd($data);
        sleep(3);
       return $data;
    }
*/

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
      
   


      $trickByCategory = $trickRepository->trickByCategory();



      // $trickByCategory = $trickRepository->findBy(['public' => true]);

      
      //on récupère le cache
      //$cache = new FilesystemAdapter();
      

       //on va mettre le contenu dans le cache à qui on attribue une clé ici trick_key

       //trick_key est la clé avec le quel le cache est identifié
       //la fonction callback va retourner l'item dans lequel se trouve le data et la durée de la disponibilité du cache


       //ATTENTION LE COMPOSANT CACHE NE MARCHE PAS  AVEC LES RELATIONS POUR BIEN FAIRE IL FAUT SERIALISER LES CHAMPS DES ENTITES RELATIONNELLES IMPLIQUÉS

       /*
       $trickByCategory =  $cache->get('trick_key', function(ItemInterface $item) use ($trickRepository){
       $item->expiresAfter(35);
       return $trickRepository->trickByCategory();

       });

*/
  
    
      // $categories =   $categorieRepository->tricksByCategory();
       //dd($trickByCategory);
     //dd($formTrcikByCategorie);
      // $tricks  = $trickRepository->trickByCategory();
     //  dd($trickByCategory);





     //  dd($tabsfinals);
     // 

        
        //$tab = [ "kevin", "joe", "rambo"];
        return $this->render('main/index.html.twig', [
            'tricks' =>     $trickByCategory,
         //   'categories' => $categories,
       
         //   'formTrcikByCategorie' =>$formTrcikByCategorie
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



#[Route('main/trick/show/{id}', name:"main.show.trick")]
/**
 * Afficher une astuce (coté profil)
 *
 * @param Trick $trick
 * @return Response
 */
public function show(Trick $trick,RatingRepository $ratingRepository, EntityManagerInterface $em, Request $request): Response{
    
  // dd($trick);
  if(!$trick){
    throw $this->createNotFoundException("Cette astuce n'existe pas ");
   }

   $rating = new Rating() ;

  $formRating = $this->createForm(RatingType::class, $rating);
  //dd($formRating->getData());
  $formRating->handleRequest($request);
  if($formRating->isSubmitted() && $formRating->isValid()){
    $rating->setUser($this->getUser());
    $rating->setTrick($trick);

    //si cette notation existe on n'a pas le droit d'en créer
    $existingRating = $ratingRepository->findOneBy(['user' => $this->getUser(), 'trick' => $trick]);
 
    if($trick->getUser() == $this->getUser()){
        $this->addFlash('warning', 'Vous ne pouvez pas noter votre propre astuce !' );
        return $this->redirectToRoute('main.show.trick', array('id' => $trick->getId()));
    }


    //si cette notation n'existe pas on en crée un nouveau
    if(!$existingRating){
        $em->persist($rating);
        $em->flush();

      //  dd($rating);
        $this->addFlash('warning', 'l\'astuce "'. $trick->getTitle(). '" a été noté !' );
        return $this->redirectToRoute('main.show.trick', array('id' => $trick->getId()));
    }else{
        $this->addFlash('success', 'Vous avez déja noté cette astuce' );
        return $this->redirectToRoute('main.show.trick', array('id' => $trick->getId()));
    }

  
  }


    return $this->render('main/showTrick.html.twig', [
        'trick' => $trick,
        'formRating' => $formRating
    ]);
}
















}




