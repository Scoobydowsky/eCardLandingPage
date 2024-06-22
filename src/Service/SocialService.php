<?php

namespace App\Service;

use App\Entity\Links;
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

    public function addNewSocial(string $name, string $url , string $icon)
    {


        try {
            $SocialProfile = new Links();
            $SocialProfile->setName($name)
                ->setUrl($url)
                ->setIconClass($icon);
            $this->entityManager->persist($SocialProfile);
            $this->entityManager->flush();
            $this->addFlash('success','Successfully added new social profile link');
        }
        catch (\Exception $exception)
        {
            $this->addFlash('error','Unexpected error on adding new social profile link');
        }
    }

    public function editSocial(Links $link, string $name, string $url , string $icon)
    {
        $data['name']= $name;
        $data['url']= $url;
        $data['icon']=$icon;

        if($data['name'] != null || $data['url'] != null){
            if($this->repository->editSocial($link,$data)){
                $this->addFlash('success','Udało się zedytować link do profilu');
                return true;
            }
            else{
                $this->addFlash('success','Nie udało się zedytować linku do profilu');
                return false;
            }
        }else{
            $this->addFlash('error','Name and Address are required');
            return false;

        }
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