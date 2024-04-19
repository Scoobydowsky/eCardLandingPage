<?php

namespace App\Service;

use App\Repository\LinksRepository;
use App\Repository\UserRepository;

class DataGetterService
{
    public function __construct(
        private UserRepository $userRepository,
        private LinksRepository $linksRepository
    )
    {

    }
    public function getUserData()
    {
        $user = $this->userRepository->findOneBy(['id'=>'1']);
        return $user;
    }

    public function getSocials()
    {
        $links = $this->linksRepository->findAll();
        return  $links;
    }
    public function getSocialObjectById(int $id)
    {
        $social = $this->linksRepository->findOneBy(['id'=>$id]);
        return $social;
    }
}