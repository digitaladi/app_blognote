<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EditUserAdminType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserAdminController extends AbstractController {




/**
 * fonction pour récuperer tous les users en admin
 *
 * @param UserRepository $userRepository
 * @return Response
 */
#[Route('/admin/users', name:"home_admin_user")]
public function getUsers(UserRepository $userRepository): Response{

$users = $userRepository->findAll();
//dd($users->getTricks());
/*
foreach($users as $user) {
  dd( $user->getTricks()) ;
}

*/
return $this->render('admin/user/index.html.twig', [
    'users' => $users,
    //'is_actif' => true
]);

// 
}




#[Route('/admin/users/edit/{id}', name:"admin_user_edit", methods: ['GET', 'POST'])]

public function edit(User $user, EntityManagerInterface $em, Request $request): Response{

    $userFormAdmin = $this->createForm(EditUserAdminType::class, $user);
    
   //dd($userFormAdmin);
    $userFormAdmin->handleRequest($request);
  
    if($userFormAdmin->isSubmitted() && $userFormAdmin->isValid()){
 
       // dd("fff");

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'L\'utilisateur a été modifiée');
        return $this->redirectToRoute('home_admin_user');
    }

        return $this->render('admin/user/edit.html.twig', [
        'userFormAdmin' => $userFormAdmin,
    ]);
}




#[Route('/admin/users/delete/{id}', name:"admin_user_delete")]
public function delete(User $user, EntityManagerInterface $em):Response{


    if($user){
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'L\'utilisateur  a été supprimé');
        return $this->redirectToRoute('home_admin_user');
    }else{
        $this->addFlash('warning', 'L\'utilisateur n a pas été trouvé');
    }


 }




#[Route('/admin/users/show/{id}', name:"admin_user_show")]
public function show(User $user, EntityManagerInterface $em){
    //dd($user);
    if(!$user){
        throw $this->createNotFoundException("Cet utilisiteur n'existe pas");
       }

    return $this->render('admin/user/show.html.twig', ['user' => $user]);

}







}