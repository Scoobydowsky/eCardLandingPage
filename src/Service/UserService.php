<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UserService
{
    private $user ;
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
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
}