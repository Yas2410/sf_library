<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
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
     * @route("/book/{id}", name="book")
     */
    public function book(BookRepository $bookRepository, $id)
    {
        $books = $bookRepository->find($id);

        return $this->render('book.html.twig', [
            'books' => $books
        ]);
    }

}

