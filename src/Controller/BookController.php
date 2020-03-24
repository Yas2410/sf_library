<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/books", name="books")
     */
    public function books (BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('books.html.twig', [
            'books' => $books
            ]);
    }

    /**
     * @route("/book/show/{id}", name="book")
     */
    public function book(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        return $this->render('book.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @route("/book/insert", name="book_insert")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    /*public function insertBook (EntityManagerInterface $entityManager, Request $request)
    {
        $book = new Book();

        $book->setTitle("L'amie Prodigieuse");
        $book->setAuthor("Elena FERRANTE");
        $book->setNbPages("440");
        $book->setResume("Test insertion nouveau livre");

        $entityManager->persist($book);
        $entityManager->flush();

        return new Response( "test");

    }*/

    public function insertBook (EntityManagerInterface $entityManager, Request $request)
    {
        $book = new Book();

        //Récupérer les éléments à partir de l'URL

        $title = $request->query->get("title");
        $author = $request->query->get("author");
        $nbPages = $request->query->get("nbPages");
        $resume = $request->query->get("resume");

        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setNbPages($nbPages);
        $book->setResume($resume);

        $entityManager->persist($book);
        $entityManager->flush();
        return new Response( "test2");
    }


}

