<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    //Création d'une méthode : selectionner un livre en fonction d'un mot défini et contenu dans le résumé
    public function getByWordInResume($word)
    {

        $queryBuilder = $this->createQueryBuilder('book');
        $query = $queryBuilder->select('book')
            ->where('book.resume LIKE :word')
            //SECURITE
            ->setParameter('word', '%' . $word . '%')
            ->getQuery();

        $results = $query->getResult();
        //Méthode qui me retourne les résultats que l'on va ensuite appeler (affichier) dans mon bookcontroller
        return $results;

    }

}
