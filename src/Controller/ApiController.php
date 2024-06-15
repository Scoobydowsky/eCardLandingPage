<?php

namespace App\Controller;

use App\Service\DataGetterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(
        protected DataGetterService $getterService
    )
    {

    }
    #[Route('/api/list',methods: "GET")]
    public function listAllSocials()
    {
        $socials = $this->getterService->getSocials();

        return $this->json($socials);
    }
    
}