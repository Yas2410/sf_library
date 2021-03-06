<?php

namespace App\Controller\Admin;

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
     * @Route("admin/authors", name="admin_authors")
     */
    public function authors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();
        return $this->render('admin/author/authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @route("admin/author/show/{id}", name="admin_author")
     */
    public function author(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);

        return $this->render('admin/author/author.html.twig', [
            'author' => $author
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


    /*public function insertAuthor(EntityManagerInterface $entityManager, Request $request)
    {
        $author = new Author();

        //Récupérer les éléments à partir de l'URL
        $firstName = $request->query->get("firstName");
        $lastName = $request->query->get("lastName");
        $birthDate = $request->query->get("birthDate");
        $deathDate = $request->query->get("deathDate");
        $biography = $request->query->get("biography");
        $img = $request->query->get("img");

        $author->setfirstName($firstName);
        $author->setlastName($lastName);
        $author->setbirthDate(new \DateTime($birthDate));
        if (!is_null($deathDate)) {
            $author->setdeathDate(new \DateTime($deathDate));
        }

        $author->setBiography($biography);
        $entityManager->persist($author);
        $entityManager->flush();
        return new Response("TEST");
    }*/

    //Supprimer un élément de la BDD

    /**
     * @route("admin/author/delete", name="admin_author_delete")
     */
    public function deleteAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        $author = $authorRepository->find($id);
        $entityManager->remove($author);
        $entityManager->flush();

        return new Response("L'auteur a bien été supprimé de la BDD");
    }

    //Supprimer un élément de la BDD via URL

    /**
     * @route("admin/author/delete/{id}", name="admin_author_delete")
     */
    public function deleteAuthorUrl(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        $author = $authorRepository->find($id);
        $entityManager->remove($author);
        $entityManager->flush();

        return new Response("L'auteur a bien été supprimé de la BDD");
    }

    //METTRE A JOUR UN ELEMENT DE LA BDD

    /**
     * @route("admin/author/update/{id}", name="admin_author_update")
     */
    public function updateAuthor(
        Request $request,
        AuthorRepository $authorRepository,
        EntityManagerInterface $entityManager,
        $id
    )

    {
        $author = $authorRepository->find($id);
        $formAuthor = $this->createForm(AuthorType::class, $author);
        $formAuthor->handleRequest($request);
        if ($formAuthor->isSubmitted() && $formAuthor->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();
        }

        return $this->render('admin/author/insertAuth.html.twig', [
            'formAuthor' => $formAuthor->createView()
        ]);

    }

    /**
     * @route("admin/author/search", name="admin_author_search")
     */
    public function searchByBiography(AuthorRepository $authorRepository, Request $request)
    {
        $search = $request->query->get('search');
        $authors = $authorRepository->getByWordInBiography($search);

        return $this->render('admin/author/searchBio.html.twig', [
            'search' => $search, 'authors' => $authors
        ]);
    }
}