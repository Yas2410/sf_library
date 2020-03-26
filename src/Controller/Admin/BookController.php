<?php

namespace App\Controller\Admin;

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
     * @Route("admin/books", name="admin_books")
     */
    public function books (BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('admin/book/books.html.twig', [
            'books' => $books
            ]);
    }

    /**
     * @route("admin/book/show/{id}", name="admin_book")
     */
    public function book(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        return $this->render('admin/book/book.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @route("admin/book/insert", name="admin_book_insert")
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

    //Supprimer un élément de la BDD
    /**
     * @route("admin/book/delete", name="admin_book_delete")
     */
    public function deleteBook (BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        $book = $bookRepository->find($id);
        $entityManager->remove($book);
        $entityManager->flush();

        return new Response("Le livre a bien été supprimé de la BDD");
    }

    //Supprimer un élément de la BDD via URL
    /**
     * @route("admin/book/delete/{id}", name="admin_book_delete")
     */
    public function deleteBookUrl (BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        $book = $bookRepository->find ($id);
        $entityManager->remove($book);
        $entityManager->flush();

        return new Response("Le livre a bien été supprimé de la BDD");
    }

    //METTRE A JOUR UN ELEMENT DE LA BDD
    /**
     * @route("admin/book/update/{id}", name="admin_book_update")
     */
    public function updateBook (
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager,
        $id
    )
    {
        $book = $bookRepository->find ($id);
        $book->setTitle("La Jeune Fille et La Nuit");
        $entityManager->persist($book);
        $entityManager->flush();
        return new Response ('Le titre du livre a bien été modifié!');
    }

    /**
     * @route("admin/book/search", name="admin_book_search")
     */
    public function searchByResume(BookRepository $bookRepository, Request $request)
    {
        $search = $request->query->get('search');
        $books = $bookRepository->getByWordInResume($search);

       return $this->render('admin/book/search.html.twig', [
           'search'=>$search, 'books'=>$books
       ]);
    }
}

