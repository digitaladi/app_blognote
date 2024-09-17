<?php

namespace App\Controller\Profil;

use App\Entity\Trick;
use App\Form\AddTrickFormType;
use App\Repository\UserRepository;
use App\Service\PictureService;
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
    public function index(): Response
    {
        return $this->render('profil/trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
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
                $em->persist($trick);
                $em->flush();

                $this->addFlash('success', 'L\'astuce a été ajouté');
                return $this->redirectToRoute('app_profil_trick_index');
            }


        return $this->render('profil/trick/add.html.twig', [
            'trickForm' => $trickForm,
        ]);
    }





}
