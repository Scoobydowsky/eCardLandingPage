<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
       protected UserRepository $userRepository,
       protected EntityManagerInterface $entityManager,
       protected UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function updateLogin(int $userId, string $login)
    {
        $user = $this->userRepository->findUserById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User not found.');
        }
        $user->setUsername($login);
        $this->userRepository->saveUser($user);
    }

    public function updateUserData(int $userId, string $name, string $surname, string $nick, string $description)
    {
        $user = $this->userRepository->findUserById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User not found.');
        }
        $this->userRepository->updateUserData($user, $name, $surname, $nick, $description);
    }

    public function changePassword(int $userId, string $oldPassword, string $newPassword)
    {
        $user = $this->userRepository->findUserById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User not found.');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
            throw new \Exception('Current password does not match.');
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $this->userRepository->saveUser($user);
    }
}