<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
class UserFixture extends Fixture implements FixtureGroupInterface
{     
     
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){}



    public function load(ObjectManager $manager): void
    {
       $admin1 = new User();
       $date='12/24/2000';
        
       $admin1->setUsername('madou');
       $admin1->setEmail('admin@gmail.tn');
       $admin1->setPassword($this->hasher->hashPassword($admin1,'admin'));
       $admin1->setRoles(['ROLE_ADMIN']);
       $admin1->setBanned(false);
       $admin1->setPhone(88888);
       $admin1->setDateJoin(new \DateTime($date));
       $admin1->setImage('aa');
        
        $manager->persist($admin1);

        for($i=1;$i<=5;$i++){
         
            
            $user =new User();
            $date='12/24/2000';

            $user->setUsername('aa');
            $user->setEmail("user$i@gmail.tn");            
            $user->setPassword($this->hasher->hashPassword($user,'user'));
            $user->setBanned(false);
            $user->setPhone(88888);
            $user->setDateJoin(new \DateTime($date));
            $user->setImage('uuu');
            $manager->persist($user);

        }
        $manager->flush();
    }


    public static function getGroups(): array
    {
      
        return ['user'];

    }
}
