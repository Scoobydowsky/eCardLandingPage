<?php


declare(strict_types=1);

namespace App\Test\Service;

use App\Entity\Links;
use App\Repository\LinksRepository;
use App\Service\SocialService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

class SocialServiceTest extends TestCase
{
    private $repository;
    private $entityManager;
    private $socialService;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LinksRepository::class);
        $this->entityManager = Mockery::mock(EntityManagerInterface::class);
        $this->socialService = new SocialService($this->repository, $this->entityManager);
    }

    public function testEditSocial(): void
    {
        $link = new Links();
        $name = 'Test Name';
        $url = 'Test Url';
        $icon = 'Test Icon';

        $this->repository->shouldReceive('updateSocial')
            ->with($link, $name, $url, $icon)
            ->andReturn(true);

        $result = $this->socialService->editSocial($link, $name, $url, $icon);

        $this->assertTrue($result);
    }

}