<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthentificatorAuthenticator;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

//cette class  a été généné par la commande symfony console make:registration
//c'est la route pour s'enrengistrer
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthentificatorAuthenticator $Authenticator, UserAuthenticatorInterface $userAuthenticator, EntityManagerInterface $entityManager, JWTService $JWT, SendEmailService $mail): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email


          

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

            //dd($token);


            $mail->send(
                'no-reply@blognote.test',
                $user->getEmail(),
                'Activation de votre compte sur le site blognote',
                'register',
                compact('user', 'token') // ['user' => $user, token => $token]
            );

            $this->addFlash('success', 'Utilisateur inscrit, Veuillez cliquer sur le lien reçu pour confirmer votre adresse e-mail');

            //return $security->login($user, UserAuthentificatorAuthenticator::class, 'main');
            return $userAuthenticator->authenticateUser($user,$Authenticator,$request);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }



    //vérifie si le token est valide si c'est le cas on s'inscrie
    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifUser($token, JWTService $JWT, UserRepository $userRepository, EntityManagerInterface $entityManager): Response{

        //on vérifie si le token est valide (pas d'erreur, pas éxpiré, et une signature correcte)

        if($JWT->isValid($token) && !$JWT->isExpired($token) && $JWT->check($token, $this->getParameter('app.jwt_secret'))){
            //le token est valide 

            //on récupre le payloads
            $payload = $JWT->getPayload($token);
            //dd($payload);

            //on récupre le user
            $user = $userRepository->find($payload['user_id']);

            //on vérifie qu(on a bien un user et qu'il n'est pas actif)

            if($user && ! $user->isVerified()){
                $user->setVerified(true);
                $entityManager->flush();
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_home');
            }
        }

        $this->addFlash('danger', 'Le token est invalide ou à expiré ');
        return $this->redirectToRoute('app_login');



    }
}
