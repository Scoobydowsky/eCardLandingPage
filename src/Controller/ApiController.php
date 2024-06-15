<?php

namespace App\Controller;

use App\Entity\Links;
use App\Service\DataGetterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private DataGetterService $getterService;
    private const AUTH_TOKEN = '5bb70c57-9ca1-4bbf-b189-5d01cd1a80e5'; // W praktyce przechowuj tokeny w bezpiecznym miejscu, np. w zmiennych środowiskowych

    public function __construct(
        EntityManagerInterface $entityManager,
        DataGetterService $getterService
    ) {
        $this->entityManager = $entityManager;
        $this->getterService = $getterService;
    }

    #[Route('/api/list', methods: ["GET"])]
    public function listAllSocials()
    {
        $socials = $this->getterService->getSocials();

        return $this->json($socials);
    }

    #[Route('/api/add', methods: ["POST"])]
    public function addNewSocialViaApi(Request $request)
    {
        // Sprawdzenie tokena autoryzacyjnego
        $token = $request->headers->get('Authorization');
        if ($token !== 'Bearer ' . self::AUTH_TOKEN) {
            return $this->json([
                'status' => 'error',
                'message' => 'Unauthorized or invalid token'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $url = $data['url'];
        $icon = $data['icon'];

        if (!$name || !$url) {
            return $this->json([
                'status' => 'error',
                'message' => 'Missing required parameters'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $SocialProfile = new Links();
            $SocialProfile->setName($name)
                ->setUrl($url)
                ->setIconClass($icon);

            $this->entityManager->persist($SocialProfile);
            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Successfully added new social profile link'
            ], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->json([
                'status' => 'error',
                'message' => 'Unexpected error on adding new social profile link'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
