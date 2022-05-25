<?php

namespace App\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;

class MovieTransformer
{
    /**
     * @var GenreRepository
     */
    private $repository;

    public function __construct(GenreRepository $repository)
    {
        $this->repository = $repository;
    }

    public function arrayToMovie(array $data): Movie
    {
        $movie = (new Movie())
            ->setTitle($data['Title'])
            ->setPoster($data['Poster'])
            ->setCountry($data['Country'])
            ->setReleasedAt(strtotime($data['Released']) ? new \DateTimeImmutable($data['Released']) : new \DateTimeImmutable($data['Year']))
            ->setPrice(5.0)
            ->setRated($data['Rated'])
            ->setRating($data['imdbRating'])
            ->setImdbId($data['imdbID'])
            ->setPlot($data['Plot'])
            ->setDirectors($data['Director'])
            ->setWriters($data['Writer'])
            ->setActors($data['Actors'])
        ;

        $arrayGenres = explode(', ', $data['Genre']);
        foreach ($arrayGenres as $genre) {
            $genreEntity = $this->repository->findOneBy(['name' => $genre]);
            $movie->addGenre($genreEntity ?? (new Genre())->setName($genre)->setPoster($data['Poster']));
        }

        return $movie;
    }
}