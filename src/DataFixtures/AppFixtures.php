<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {

    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $plainPassword= 'admin';

        $user = new User();
        $hashedPassword=  $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setUsername('admin')
            ->setName('Admin')
            ->setSurname('Adminowsky')
            ->setNick('Admino')
            ->setRoles(['ROLE_ADMIN'])
            ->setDescription('Hello Word')
            ->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->flush();
    }
}
