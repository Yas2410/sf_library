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

}