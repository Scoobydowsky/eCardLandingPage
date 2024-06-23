<?php

namespace App\Controller;

use App\Controller\PageInterface;
use App\Repository\LinksRepository;
use App\Repository\UserRepository;
use App\Service\DataGetterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private LinksRepository $linksRepository
    )
    {

    }

    #[Route('/',name:'app_homepage')]
    public function renderHomepage()
    {
        $userData = $this->userRepository->find(['id'=>'1']);

        $profiles = $this->linksRepository->findAll();
        
        return $this->render('homepage.html.twig',
        [
            'user'=>$userData,
            'profiles'=>$profiles
        ]);
    }
}