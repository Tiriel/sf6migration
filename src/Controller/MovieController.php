<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie", name="app_movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function search(Request $request, MovieRepository $repository, MovieProvider $movieProvider)
    {
        $title = $request->query->get('title');
        $movie = $repository->findOneBy(['title' => $title]);

        if (!$movie) {
            $movie = $movieProvider->getMovieByTitle($title);
            $repository->add($movie);
        }

        return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId()]);
    }
}
