<?php

namespace App\Controller\Front;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("front/authors", name="front_authors")
     */
    public function authors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();
        return $this->render('front/author/authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @route("front/author/show/{id}", name="front_author")
     */
    public function author(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);

        return $this->render('front/author/author.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @route("front/author/search", name="front_author_search")
     */
    public function searchByBiography(AuthorRepository $authorRepository, Request $request)
    {
        $search = $request->query->get('search');
        $authors = $authorRepository->getByWordInBiography($search);

        return $this->render('front/author/searchBio.html.twig', [
            'search' => $search, 'authors' => $authors
        ]);
    }

    /**
     * @route("admin/author/insert", name="admin_author_insert")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */

    public function insertAuthor (Request $request, EntityManagerInterface $entityManager)
    {
        // Création d'un nouvel auteur afin de le lier au formulaire
        $author = new Author ();

        //Création du formulaire que je lie au nouvel auteur
        $formAuthor = $this->createForm(AuthorType::class, $author);

        //Je demande à mon formulaire (ici $formAuthor) de gérer les données POST
        $formAuthor->handleRequest($request);

        //Si les données envoyées depuis le formulaire sont valides :
        if ($formAuthor->isSubmitted() && $formAuthor->isValid()) {

        //J'enregistre les auteurs
            $entityManager->persist($author);
            $entityManager->flush();
        }

        return $this->render('admin/author/insertAuth.html.twig', [
            'formAuthor' => $formAuthor->createView()
        ]);

    }


}