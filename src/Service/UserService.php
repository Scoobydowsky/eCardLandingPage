<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService extends AbstractController
{
    private $user ;
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
        try {
            $this->user= $this->userRepository->findOneBy(['id'=>'1']);
        }catch (EntityNotFoundException $exception)
        {
            throw $exception;
        }

    }
    public function updateLogin(string $login)
    {
        $this->user->setUsername($login);
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    public function updateUserData(string $name , string $surname ,
                                   string $nick , string $description)
    {
        $this->user->setName($name)
            ->setSurname($surname)
            ->setNick($nick)
            ->setDescription($description);
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }
    public function changePassword(string $oldPassword, string $newPassword)
    {
        if($this->passwordHasher->isPasswordValid($this->user,$oldPassword)){
            $hashedPassword = $this->passwordHasher->hashPassword($this->user, $newPassword);
            $this->user->setPassword($hashedPassword);
            $this->entityManager->persist($this->user);
            $this->entityManager->flush();
        }else
        {
            $this->addFlash('error','Current password won\'t match ');
        }
    }
}