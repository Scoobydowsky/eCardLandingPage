<?php

namespace App\Controller;

use App\Controller\PageInterface;
use App\Service\DataGetterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    public function __construct(
        Private DataGetterService $getterService,
    )
    {

    }

    #[Route('/',name:'app_homepage')]
    public function renderHomepage()
    {
        $userData = $this->getterService->getUserData();
        $profiles = $this->getterService->getSocials();
        
        return $this->render('homepage.html.twig',
        [
            'user'=>$userData,
            'profiles'=>$profiles
        ]);
    }
}