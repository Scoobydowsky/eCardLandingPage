<?php

namespace App\Controller;

use App\Entity\Links;
use App\Repository\LinksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private const AUTH_TOKEN = '5bb70c57-9ca1-4bbf-b189-5d01cd1a80e5'; // W praktyce przechowuj tokeny w bezpiecznym miejscu, np. w zmiennych środowiskowych
    private const VALID_AUTH_TOKEN = 'Bearer ' . self::AUTH_TOKEN;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LinksRepository $linksRepository
    ) {
    }

    #[Route('/api/list', methods: ["GET"])]
    public function listAllSocials(): JsonResponse
    {
        $allSocials = $this->linksRepository->findAll();
        $allSocials = array_map(static fn (Links $social): array => $social->toArray(), $allSocials);
        return $this->json($allSocials);
    }

    #[Route('/api/add', methods: ["POST"])]
    public function addNewSocialViaApi(Request $request): JsonResponse
    {
        try {
            $this->validateToken($request);
        } catch (\Exception $exception) {
            return $this->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $payload = json_decode($request->getContent(), true);
        try {
            $socialProfile = Links::fromPayload($payload['name'], $payload['url'], $payload['icon']);
            $this->entityManager->persist($socialProfile);
            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Successfully added new social profile link',
            ], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->json([
                'status' => 'error',
                'message' => 'Unexpected error on adding new social profile link',
                'exceptionMessage' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //$name = $payload['name'];
        //$url = $payload['url'];
        //$icon = $payload['icon'];
        //if (!$name || !$url) {
        //    return $this->json([
        //        'status' => 'error',
        //        'message' => 'Missing required parameters',
        //    ], Response::HTTP_BAD_REQUEST);
        //}
        //
        //try {
        //    $socialProfile = (new Links())
        //        ->setName($name)
        //        ->setUrl($url)
        //        ->setIconClass($icon);
        //    $this->entityManager->persist($socialProfile);
        //    $this->entityManager->flush();
        //
        //    return $this->json([
        //        'status' => 'success',
        //        'message' => 'Successfully added new social profile link',
        //    ], Response::HTTP_CREATED);
        //} catch (\Exception $exception) {
        //    return $this->json([
        //        'status' => 'error',
        //        'message' => 'Unexpected error on adding new social profile link',
        //    ], Response::HTTP_INTERNAL_SERVER_ERROR);
        //}
    }

    /** @throws \Exception */
    private function validateToken(Request $request): void
    {
        $token = $request->headers->get('Authorization');
        if ($token !== self::VALID_AUTH_TOKEN) {
            throw new \Exception('Unauthorized or invalid token');
        }
    }
}
