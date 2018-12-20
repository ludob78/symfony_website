<?php

namespace Lddt\MainBundle\Repository;

/**
 * DrawRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DrawRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllDraws(){
        $q=$this->getEntityManager()
            //Jointure sur l'attribut category de l'entité LddtMainBundle:Draw
            ->createQuery('select d,c,p from LddtMainBundle:Draw d 
                                INNER JOIN d.category c 
                                INNER JOIN d.pic p 
                                WHERE d.isOnline=TRUE 
                                ORDER BY d.updatedAt DESC');
        return $q->getResult();
    }

    public function findAllDrawsByCat($category){
        //Constructeur de requête
        $q=$this->createQueryBuilder("d")
            ->where("d.category=:cat")
            ->andWhere('d.isOnline=true')
            ->join("d.category","c")
            ->join("d.pic","p")
            ->join("d.author","a")
            ->setParameter("cat",$category)
            ->addSelect("c","p","a");
        return $q->getQuery()->getResult();
    }

    public function findAllDrawsByAuthor($user){
        //Constructeur de requête
        $q=$this->createQueryBuilder("d")
            ->where("d.author=:u")
            ->andWhere('d.isOnline=true')
            ->join("d.category","c")
            ->join("d.pic","p")
            ->join("d.author","a")
            ->setParameter("u",$user)
            ->addSelect("c","p","a");
        return $q->getQuery()->getResult();
    }

    public function findAllDrawsByColor(array $colors){
        $q=$this->createQueryBuilder("d");
            $q->join("d.color","c")
                ->join("d.category","cat")
                ->join("d.pic","p")
                ->join("d.author","a")
                ->where($q->expr()->in("c.name",$colors))
                ->andWhere('d.isOnline=true')
                ->addSelect("c","cat","p","a");
            return $q->getQuery()->getResult();
    }

    public function findAllDrawsByTag(array $tags){
        $q=$this->createQueryBuilder("d");
            $q->join("d.tag","t")
                ->join("d.category","cat")
                ->join("d.pic","p")
                ->join("d.author","a")
                ->where($q->expr()->in("t.name",$tags))
                ->andWhere('d.isOnline=true')
                ->addSelect("t","cat","p","a");
            return $q->getQuery()->getResult();
    }

    public function findAllDrawsToPushOnline(){
        $q=$this->createQueryBuilder('d')
            ->join("d.category","cat")
            ->join("d.pic","p")
            ->where('d.isOnline = false')
            ->addSelect('cat','p');
        return $q->getQuery()->getResult();
    }
}