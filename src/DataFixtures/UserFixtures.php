<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher){}

    public function load(ObjectManager $manager): void
    {
         $newUser = new User();
        $newUser->setUsername('User');
         $newUser->setPassword($this->hasher->hashPassword($newUser, 'azerty'));
         $newUser->setEmail('user@demo.fr');
         $newUser->setVerified(true);
 
         $manager->persist($newUser);



         $newUser = new User();
         $newUser->setUsername('digitaladi');
         $newUser->setPassword($this->hasher->hashPassword($newUser, 'aladi'));
         $newUser->setEmail('admin@demo.fr');
         $newUser->setVerified(true);
         $newUser->setRoles(['ROLE_ADMIN']);
         $this->setReference('Admin', $newUser);
         $manager->persist($newUser);

         $manager->flush();
    }
}