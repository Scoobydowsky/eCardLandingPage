<?php

namespace App\Repository;

use App\Entity\Links;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Links>
 *
 * @method Links|null find($id, $lockMode = null, $lockVersion = null)
 * @method Links|null findOneBy(array $criteria, array $orderBy = null)
 * @method Links[]    findAll()
 * @method Links[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Links::class);
    }

    public function save(Links $link): void
    {
        try{
            $this->getEntityManager()->persist($link);
            $this->getEntityManager()->flush();
        }catch (\Exception $exception){
            throw new \Exception('Nie udało dodać się wpisu do DB');
        }
    }

    public function addNewSocial(string $name, string $url, string $icon): void
    {
        $socialLink = new Links();
        $socialLink->setName($name);
        $socialLink->setUrl($url);
        $socialLink->setIconClass($icon);
        $this->save($socialLink);
    }

    public function updateSocial(Links $link, string $name, string $url, string $icon): bool
    {
        try {
            $link->setName($name);
            $link->setUrl($url);
            $link->setIconClass($icon);
            $this->save($link);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    //    /**
    //     * @return Links[] Returns an array of Links objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Links
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
