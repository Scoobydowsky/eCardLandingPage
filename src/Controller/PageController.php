<?php

namespace App\Controller;

use App\Controller\PageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController implements PageInterface
{

    #[Route('/',name:'app_homepage')]
    public function renderHomepage()
    {


        return $this->render('homepage.html.twig');
    }
}