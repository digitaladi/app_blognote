<?php


namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Form\AddTrickAdminType;
use App\Repository\TrickRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
// 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/admin/tricks', name:"app_admin_trick_")]
class TrickAdminController  extends AbstractController{
    

/**
 * Affiche les astuces coté admin
 *
 * @param TrickRepository $trickRepository
 * @return Response
 */
#[Route('/', name:"index")]
    public function getTricks( TrickRepository $trickRepository ) : Response{

       $tricks =  $trickRepository->findAll();
      //  dd($tricks);
        return $this->render('admin/trick/index.html.twig', [
            'tricks' => $tricks,
            //'is_actif' => true
        ]);
    }


    /**
     * Ajoute un astuce coté admin
     *
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param PictureService $pictureService
     * @param EntityManagerInterface $em
     * @return void
     */
    #[Route('/add', name:"add")]
    public function addTrickAdmin(Request $request, SluggerInterface $slugger, PictureService $pictureService, EntityManagerInterface $em){
        
        $trick = new Trick();

        $trickFormAdmin = $this->createForm(AddTrickAdminType::class, $trick);

        //on traite le formualaire
        $trickFormAdmin->handleRequest($request);

        //on vérifie si le formulaire est envoyé et  valide 

        if($trickFormAdmin->isSubmitted() && $trickFormAdmin->isValid()){

                //on crée le slug à parti du nom de l'astuce
                $slug = strtolower($slugger->slug($trick->getTitle()) );
              //     dd($this->getUser());
                $trick->setUser($this->getUser());
              //  dd($featuredImage);
               $featuredImage = $trickFormAdmin->get('featureimage')->getData();
              
               $image = $pictureService->square($featuredImage, '/tricks', 200);
                $trick->setFeatureimage($image)   ;



                //dd($slug);
                //on assgine uen valeur au slug de  l'astuce
                $trick->setSlug($slug);
                $trick->setCreatedAt(new \DateTimeImmutable());
                $trick->setUpdatedAt(new \DateTimeImmutable());
                $em->persist($trick);
                $em->flush();

                $this->addFlash('success', 'L\'astuce a été ajouté');
                return $this->redirectToRoute('app_admin_trick_index');
            }


        return $this->render('admin/trick/add.html.twig', [
            'trickFormAdmin' => $trickFormAdmin,
        ]);
              
              
        }



   
    #[Route('/edit/{id}', name:"edit", methods: ['GET', 'POST'])]
    public function EditTrickAdmin(EntityManagerInterface $em, Trick $trick,Request $request, SluggerInterface $slugger, PictureService $pictureService) : Response{
            //paramconverter permet de de dire que $trick en parametre correspond l'id du route

         //   $trick = $trickRepository->findOneBy(["id" => $id]);
           // dd($trick->getUser()->getId());
            $trickFormAdmin = $this->createForm(AddTrickAdminType::class, $trick);

                 //on traite le formualaire
             $trickFormAdmin->handleRequest($request);

            if($trickFormAdmin->isSubmitted() && $trickFormAdmin->isValid()){
               // dd($trickFormAdmin->getData());
                //on crée le slug à parti du nom de l'astuce
                $slug = strtolower($slugger->slug($trick->getTitle()) );
              //     dd($this->getUser());
                $trick->setUser($trick->getUser());
              //  dd($featuredImage);
               $featuredImage = $trickFormAdmin->get('featureimage')->getData();
              
               $image = $pictureService->square($featuredImage, '/tricks', 200);
                $trick->setFeatureimage($image)   ;



                //dd($slug);
                //on assgine uen valeur au slug de  l'astuce
                $trick->setSlug($slug);
                $trick->setCreatedAt(new \DateTimeImmutable());
                $trick->setUpdatedAt(new \DateTimeImmutable());
                $em->persist($trick);
                $em->flush();

                $this->addFlash('success', 'L\'astuce a été modifiée');
                return $this->redirectToRoute('app_admin_trick_index');
            }
            return $this->render('admin/trick/edit.html.twig', [
                    'trickFormAdmin' => $trickFormAdmin,
                ]);
        }

   


         #[Route('/delete/{id}', name:"delete")]
        public function deleteTrickAdmin(Trick $trick, EntityManagerInterface $em):Response{


            if($trick){
                $em->remove($trick);
                $em->flush();
                $this->addFlash('success', 'L\'astuce a été supprimé');
                return $this->redirectToRoute('app_admin_trick_index');
            }else{
                $this->addFlash('warning', 'L\'astuce n a pas été trouvé');
            }


    }



    #[Route('/show/{id}', name:"show")]
    public function showTrickAdmin(Trick $trick): Response{
        
       // dd($trick);

       if(!$trick){
        throw $this->createNotFoundException("Cette astuce n'existe pas ");
       }
        return $this->render('admin/trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }


}