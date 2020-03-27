<?php

namespace App\Controller\Front;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(BookRepository $bookRepository,
    AuthorRepository $authorRepository)
    {
        $lastBooks = $bookRepository->findBy([], ['id' => 'DESC'], 2, 0);
        $lastAuthors = $authorRepository->findBy([], ['id' => 'DESC'], 2, 0);
        return $this->render('front/home.html.twig', [
            'books' => $lastBooks,
            'authors' => $lastAuthors
            ]);


    }

}