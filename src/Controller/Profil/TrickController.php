<?php

namespace App\Controller\Profil;


use App\Entity\Trick;
use App\Form\AddTrickFormType;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Service\PictureService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profil/trick', name: 'app_profil_trick_')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TrickRepository $trickRepository, EntityManagerInterface $em, UserRepository $userRepository): Response
    {


        $lastTricks = $trickRepository->findOneBy([], ['id' => 'DESC']);
        $tricks  = $trickRepository->findBy([], ['id'=> 'DESC'], 8);

        //on récupère les utilisateurs qui ont plus de posts par ordre 
        $bestAuthors = $userRepository->getUserByTricks(2);
        //dd($tricks);

        return $this->render('profil/trick/index.html.twig', 
            compact('tricks', 'lastTricks','bestAuthors')
        );
    }




    #[Route('/addTrick', name: 'add')]
    public function addTrick(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, UserRepository $userRepository, PictureService $pictureService): Response
    {
        //on initialise  l'astuce
        $trick = new Trick();

      


        //on initialise le formulaire lié à l'astuce
        $trickForm = $this->createForm(AddTrickFormType::class, $trick);
        //dd($this->getUser());

          //on traite le formualaire
          $trickForm->handleRequest($request);

                //on vérifie si le formulaire est envoyé et  valide 
                if($trickForm->isSubmitted() && $trickForm->isValid()){

                //on crée le slug à parti du nom de l'astuce
                $slug = strtolower($slugger->slug($trick->getTitle()) );
              //     dd($this->getUser());
                $trick->setUser($this->getUser());
              //  dd($featuredImage);
               $featuredImage = $trickForm->get('featureimage')->getData();
              
               $image = $pictureService->square($featuredImage, '/tricks', 300);
                $trick->setFeatureimage($image)   ;



                //dd($slug);
                //on assgine uen valeur au slug de  l'astuce
                $trick->setSlug($slug);
                $trick->setCreatedAt(new \DateTimeImmutable());
                $trick->setUpdatedAt(new \DateTimeImmutable());
                $em->persist($trick);
                $em->flush();

                $this->addFlash('success', 'L\'astuce a été ajouté');
                return $this->redirectToRoute('app_profil_trick_index');
            }


        return $this->render('profil/trick/add.html.twig', [
            'trickForm' => $trickForm,
        ]);
    }


public function removeTrick($trick){

}


}
