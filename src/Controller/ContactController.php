<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();


        if($this->getUser()){
       //  dd($this->getUser()->getUserIdentifier());
            $contact->setPseudo($this->getUser()->getUsername());
            $contact->setEmail($this->getUser()->getEmail());
        }


       $formContact =  $this->createForm(ContactType::class, $contact);


        $formContact->handleRequest($request);

        if($formContact->isSubmitted() && $formContact->isValid()){
            //dd($formContact->getData());
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'Votre demande a été envoyé !' );
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
          
            'formContact' => $formContact
        ]);
    }
}
