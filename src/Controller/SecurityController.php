<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


//Cette classe concerne le controller de l'authentification

class SecurityController extends AbstractController
{
    //pour se connecter 
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        //la route si on est déja connecté si on veut se connecter
/*
        if ($this->getUser()) {
             return $this->redirectToRoute('target_path');
         }
*/
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    //pour se déconnecter 
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/mot-de-passe-oublie', name:'forgotten_password')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, JWTService $JWT):Response{

        $form =     $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //le formualire est envoyé est valide
            
            //on va chercher l'utilisateur dans le bdd qui l'email rentrée dans le formulaire
            $user = $userRepository->findOneByEmail($form->get('email')->getData());
                       
            //on verifie si on a un utilisateur
            if($user){
                //on a un utilisateur
  
                //on génére u token
            //header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            //payload
            $payload = [
                'user_id' => $user->getId()
            ];


            //générer le token
          $token =   $JWT->generate($header,$payload, $this->getParameter("app.jwt_secret"));
        

          //on genere l'url vers  reset_password
          $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            }
           // dd($user);
            $this->addFlash('danger', 'un probléme est survenu');
            return $this->redirectToRoute('app_login');

        }
       // dd("ok");

       return $this->render('security/reset_password_request.html.twig', [
        'requestPassForm' => $form->createView()
       ]);
    }



    #[Route('/mot-de-passe-oublie/{token}', name:'reset_password')]
    public function resetPassword(Request $request, UserRepository $userRepository, JWTService $JWT){
        dd("ok");
    }


}
