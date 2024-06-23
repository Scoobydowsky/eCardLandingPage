<?php

namespace App\Service;

use App\Entity\Links;
use App\Repository\LinksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SocialService
{
//
    private array $data;

    public function __construct(
        private LinksRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function addNewSocial(string $name, string $url, string $icon): void
    {
        $this->repository->addNewSocial($name, $url, $icon);
    }

    public function editSocial(Links $link, string $name, string $url, string $icon): bool
    {
        if (!empty($name) && !empty($url)) {
            if ($this->repository->updateSocial($link, $name, $url, $icon)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function deleteSocial(int $id):bool
    {
        $socialProfile =$this->repository->findOneBy(['id'=>$id]);
        if ($socialProfile === null) {
            $exception = new EntityNotFoundException();
            return false;
        }
        try {
            $this->entityManager->remove($socialProfile);
            $this->entityManager->flush();
        }catch (ORMException $exception){
            echo $exception->getMessage();
            return false;
        }
        catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return true;
    }

}