<?php

namespace App\Service;

use App\Repository\LinksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SocialService extends AbstractController
{
    public function __construct(
        private LinksRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {

    }
    public function deleteSocial(int $id):bool
    {
        $socialProfile =$this->repository->findOneBy(['id'=>$id]);
        if ($socialProfile === null) {
            $exception = new EntityNotFoundException();
            $this->addFlash('error','Nie znaleziono linku o podanym ID' . $exception->getMessage());
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
        $this->addFlash('success','Pomyślnie usunięto link ');
        return true;
    }
}