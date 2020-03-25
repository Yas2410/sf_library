<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/authors", name="authors")
     */
    public function authors (AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();
        return $this->render('authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @route("/author/show/{id}", name="author")
     */
    public function author(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);

        return $this->render('author.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @route("/author/insert", name="author_insert")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */

    public function insertAuthor (EntityManagerInterface $entityManager, Request $request)
    {
        $author = new Author();

        //Récupérer les éléments à partir de l'URL
        $firstName = $request->query->get("firstName");
        $lastName = $request->query->get("lastName");
        $birthDate = $request->query->get("birthDate");
        $deathDate = $request->query->get("deathDate");
        $biography = $request->query->get("biography");

        $author->setfirstName($firstName);
        $author->setlastName($lastName);
        $author->setbirthDate(new \DateTime($birthDate));
        if (!is_null($deathDate))
        {
            $author->setdeathDate(new \DateTime($deathDate));
        }

        $author->setBiography($biography);
        $entityManager->persist($author);
        $entityManager->flush();
        return new Response( "TEST");
    }

    //Supprimer un élément de la BDD
    /**
     * @route("/author/delete", name="author_delete")
     */
    public function deleteAuthor (AuthorRepository $authorRepository, EntityManagerInterface $entityManager)
    {
        $author = $authorRepository->find(10);
        $entityManager->remove($author);
        $entityManager->flush();

        return new Response("L'auteur a bien été supprimé de la BDD");
    }

    //Supprimer un élément de la BDD via URL
    /**
     * @route("/author/delete/{id}", name="author_delete")
     */
    public function deleteAuthorUrl (AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        $author = $authorRepository->find ($id);
        $entityManager->remove($author);
        $entityManager->flush();

        return new Response("L'auteur a bien été supprimé de la BDD");
    }

    //METTRE A JOUR UN ELEMENT DE LA BDD
    /**
     * @route("/author/update/{id}", name="author_update")
     */
    public function updateAuthor (
        AuthorRepository $authorRepository,
        EntityManagerInterface $entityManager,
        $id
    )
    {
        $author = $authorRepository->find ($id);
        $author->setFirstName("");
        $entityManager->persist($author);
        $entityManager->flush();
        return new Response ("L\'identité de l\'auteur a été mise à jour!");
    }

    /**
     * @route("/author/search", name="author_search")
     */
    public function searchByBiography(AuthorRepository $authorRepository, Request $request)
    {
        $search = $request->query->get('search');
        $authors = $authorRepository->getByWordInBiography($search);

        return $this->render('searchBio.html.twig', [
            'search'=>$search, 'authors'=>$authors
        ]);
    }
}