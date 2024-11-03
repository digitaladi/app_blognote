<?php
namespace App\Controller\Profil;

use App\Entity\User;
use App\Form\EditUserPasswordType;
use App\Form\UserProfileEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/user', name:"app_profile_user_")]
Class UserProfileController extends AbstractController{


    #[Route('/{id}', name:"index")]
    public function index(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response{
    

      //si l'utilisateur n'est pas connecté
  if(!$this->getUser()){
      return $this->redirectToRoute("app_login ");

}

  //si l'utilisateur connecté ne correponds pas à l'utilisateur en parametre
if($this->getUser() !== $user){
  return $this->redirectToRoute("app_profile_main_index");

}


$formEditUserPassword = $this->createForm(EditUserPasswordType::class);
$formEditUserPassword->handleRequest($request);

if($formEditUserPassword->isSubmitted() && $formEditUserPassword->isValid()){
 // dd($formEditUserPassword->getData()['password']);
  if($hasher->isPasswordValid($user, $formEditUserPassword->getData()['password'])){
  
      $user->setPassword($hasher->hashPassword($user, $formEditUserPassword->getData()['newpassword']) );
      $em->persist($user);
      $em->flush();
      $this->addFlash('success', 'Le mot de passe a été modifié');
      return $this->redirectToRoute('app_profile_user_index', array('id' => $user->getId()));
  }else{
    $this->addFlash('warning', 'Le mot de passe renseigné est incorrect');
    return $this->redirectToRoute('app_profile_user_index', array('id' => $user->getId()));
  }


}





$formeditUser = $this->createForm(UserProfileEditType::class, $user);
$formeditUser->handleRequest($request);
if($formeditUser->isSubmitted() && $formeditUser->isValid()){
//if($hasher->isPasswordValid($user, $formeditUser->getData()->getPassword())){
  //dd($user);
  $em->persist($user);
  $em->flush();
  $this->addFlash('success', 'Votre profil a été modifié');
  return $this->redirectToRoute('app_profile_user_index', array('id' => $user->getId()));
//}else{
  //$this->addFlash('warning', 'Le mot de passe renseigné est incorrect');
//}



}


   // $users = $userRepository->findAll();
    //dd($users->getTricks());
    /*
    foreach($users as $user) {
      dd( $user->getTricks()) ;
    }
    
    */



    return $this->render('profil/user/index.html.twig', [
      "formeditUser" =>  $formeditUser,
      'formEditUserPassword' => $formEditUserPassword

       // 'users' => $users,
      //  
       
        //'is_actif' => true
    ]);
    
    // 
    }





    #[Route('/edit', name:"edit")]
    public function edit($user) : Response{
      return $this->render('profil/user/index.html.twig', [
        // 'users' => $users,
         //'is_actif' => true
     ]);
    }






}