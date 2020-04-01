<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookController extends AbstractController
{
    /**
     * @Route("admin/books", name="admin_books")
     */
    public function books(BookRepository $bookRepository)
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
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $slugger
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

    public function insertBook(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)

    {
        // Création d'un nouveau livre afin de le lier au formulaire
        $book = new Book ();

        //Création du formulaire que je lie au nouveau livre
        $formBook = $this->createForm(BookType::class, $book);

        //Je demande à mon formulaire (ici $formBook) de gérer les données POST
        $formBook->handleRequest($request);

        //Si les données envoyées depuis le formulaire sont valides :
        if ($formBook->isSubmitted() && $formBook->isValid()) {

            $cover = $formBook->get('cover')->getData();
            if ($cover) {
                $originalFilename = pathinfo($cover->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cover->guessExtension();

                $cover->move(
                    $this->getParameter('cover_directory'),
                    $newFilename);

                $book->setCover($newFilename);
            }
            //J'enregistre les livres
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Votre livre a bien été créé !');

        }
        return $this->render('admin/book/insert.html.twig', [
            'formBook' => $formBook->createView()
        ]);

    }

    //Supprimer un élément de la BDD

    /**
     * @route("admin/book/delete", name="admin_book_delete")
     */
    public function deleteBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
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
    public function deleteBookUrl(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        $book = $bookRepository->find($id);
        $entityManager->remove($book);
        $entityManager->flush();

        return new Response("Le livre a bien été supprimé de la BDD");
    }

    //METTRE A JOUR UN ELEMENT DE LA BDD

    /**
     * @route("admin/book/update/{id}", name="admin_book_update")
     */
    public function updateBook(
        Request $request,
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager,
        $id
    )
    {
        $book = $bookRepository->find($id);
        $formBook = $this->createForm(BookType::class, $book);
        $formBook->handleRequest($request);
        if ($formBook->isSubmitted() && $formBook->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

        }

        return $this->render('admin/book/insert.html.twig', [
            'formBook' => $formBook->createView()
        ]);
    }

    /**
     * @route("admin/book/search", name="admin_book_search")
     */
    public function searchByResume(BookRepository $bookRepository, Request $request)
    {
        $search = $request->query->get('search');
        $books = $bookRepository->getByWordInResume($search);

        return $this->render('admin/book/search.html.twig', [
            'search' => $search, 'books' => $books
        ]);
    }
}