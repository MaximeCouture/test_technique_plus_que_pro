<?php

namespace App\Helper\Movie;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\TmdbApiService;
use Symfony\Component\Serializer\SerializerInterface;

class GetMoviesHelper
{
    public function __construct(
        private readonly TmdbApiService      $tmdbApiService,
        private readonly MovieRepository     $movieRepository,
        private readonly SerializerInterface $serializer
    )
    {
    }

    /**
     * Batch load Movies from API, then load each Movie from DB to update them if they exist
     *
     * @param int[] $moviesId
     * @return Movie[]
     */
    public function getMovies(array $moviesId): array
    {
        $movies = [];
        $moviesResponses = $this->tmdbApiService->getMoviesDetails(array_unique($moviesId), true);
        foreach ($moviesResponses as $movie) {
            $DbMovie = $this->movieRepository->find($movie['id']);
            if ($DbMovie) {
                $movies[] = $this->serializer->denormalize($movie, Movie::class, 'array', ['object_to_populate' => $DbMovie]);
            } else {
                $movies[] = $this->serializer->denormalize($movie, Movie::class);
            }
        }

        return $movies;
    }
}
