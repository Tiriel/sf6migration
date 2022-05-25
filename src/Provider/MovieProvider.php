<?php

namespace App\Provider;

use App\Entity\Movie;
use App\Fetcher\MovieFetcher;
use App\Transformer\MovieTransformer;

class MovieProvider
{
    /**
     * @var MovieFetcher
     */
    private $fetcher;
    /**
     * @var MovieTransformer
     */
    private $transformer;

    public function __construct(MovieFetcher $fetcher, MovieTransformer $transformer)
    {
        $this->fetcher = $fetcher;
        $this->transformer = $transformer;
    }

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie('title', $title);
    }

    public function getMovieByImdbId(string $id): Movie
    {
        return $this->getMovie('id', $id);
    }

    private function getMovie(string $searchType, string $value): Movie
    {
        return $this->transformer->arrayToMovie(
            $this->fetcher->fetch($searchType, $value)
        );
    }
}