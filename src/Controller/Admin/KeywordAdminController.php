<?php

namespace App\Controller\Admin;

use App\Entity\Keyword;
use App\Form\AddKeywordFormType;
use App\Repository\KeywordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

//une route sur tout le controller (route mère)
//les fonctions de controller vont se baser sur la route mère
//pour écrire la route vers index de la fonction index on fait : app_admin_keywords_index 



#[Route('/admin/keywords', name: 'app_admin_keyword_')]
class KeywordAdminController extends AbstractController
{

    /**
     * Undocumented function
     *
     * @param KeywordRepository $keywordRepository
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(KeywordRepository $keywordRepository): Response   
    {   
        $keywords = $keywordRepository->findAll();
        return $this->render('admin/keyword/index.html.twig', [
            'keywords' => $keywords,
        ]);
    }

    /**
     * function d'ajout de mot de clé en admin
     *
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/add', name: 'add')]
    public function addKeywords(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        //on initialise un mot clé
        $keyword = new Keyword();

      


        //on initialise le formulaire lié au  mot clé
        $keywordForm = $this->createForm(AddKeywordFormType::class, $keyword);


          //on traite le formualaire
          $keywordForm->handleRequest($request);

                //on vérifie si le formulaire est envoyé et  valide 
                if($keywordForm->isSubmitted() && $keywordForm->isValid()){

                //on crée le slug à parti du nom du mot clé
                $slug = strtolower($slugger->slug($keyword->getName()) );
                //dd($slug);
                //on assgine uen valeur au slug du mot clé
                $keyword->setSlug($slug);
                $em->persist($keyword);
                $em->flush();

                $this->addFlash('success', 'Le mot-clé a été ajouté');
                return $this->redirectToRoute('app_admin_keyword_index');
            }


        return $this->render('admin/keyword/add.html.twig', [
            'keywordForm' => $keywordForm,
        ]);
    }




    #[Route('/edit/{id}', name:"edit", methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $em, Keyword $keyword, Request $request, SluggerInterface $slugger, ):Response{
        $keywordEditAdminForm = $this->createForm(AddKeywordFormType::class, $keyword);
        $keywordEditAdminForm->handleRequest($request);
        if($keywordEditAdminForm->isSubmitted() && $keywordEditAdminForm->isValid()){
            //on crée le slug à parti du nom du mot clé
            $slug = strtolower($slugger->slug($keyword->getName()) );
            //dd($slug);
            //on assgine uen valeur au slug du mot clé
            $keyword->setSlug($slug);
            $em->persist($keyword);
            $em->flush();
    
            $this->addFlash('success', 'Le mot clé  a été modifié');
            return $this->redirectToRoute('app_admin_keyword_index');
    
          }


        return $this->render('admin/keyword/edit.html.twig', [
        'keywordEditAdminForm' => $keywordEditAdminForm,
        ]);
    }



    #[Route('/show/{id}', name:"show")]
    public function showTrickAdmin(Keyword $keyword): Response{
        
      // dd($categorie);

       if(!$keyword){
        throw $this->createNotFoundException("Ce mot clé  n'existe pas ");
       }
        return $this->render('admin/keyword/show.html.twig', [
            'keyword' => $keyword,
        ]);
    }



    #[Route('/delete/{id}', name:"delete")]
    public function deleteTrickAdmin(Keyword $keyword, EntityManagerInterface $em):Response{


        if($keyword){
            $em->remove($keyword);
            $em->flush();
            $this->addFlash('success', 'Le mot clé a été supprimé');
            return $this->redirectToRoute('app_admin_keyword_index');
        }else{
            $this->addFlash('warning', 'Le mot clé n a pas été trouvé');
        }


}



}
