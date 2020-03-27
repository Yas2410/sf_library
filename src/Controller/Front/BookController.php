<?php

namespace App\Controller\Front;

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
     * @Route("front/books", name="front_books")
     */
    public function books(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('front/book/books.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @route("front/book/show/{id}", name="front_book")
     */
    public function book(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        return $this->render('front/book/book.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @route("front/book/search", name="front_book_search")
     */
    public function searchByResume(BookRepository $bookRepository, Request $request)
    {
        $search = $request->query->get('search');
        $books = $bookRepository->getByWordInResume($search);

        return $this->render('front/book/search.html.twig', [
            'search' => $search, 'books' => $books
        ]);
    }

}

