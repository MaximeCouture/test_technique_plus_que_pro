<?php

namespace App\Helper\Movie;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;

class ReplaceGenreByDbGenreHelper
{

    public function __construct(
        private readonly GenreRepository $genreRepository,
    )
    {
    }

    /**
     * When Genres in Movie is not a "real" persisted Genre and you want to replace it by the real one
     * If the genre doesn't currently exist in DB, it will be persisted by the cascade persist on Movie Entity
     *
     * Used to prevent cascading error when saving movies
     *
     * @param Movie $movie
     * @return void
     */
    public function replaceGenreByDbGenre(Movie $movie): void
    {
        $genres = $movie->getGenres();
        foreach ($genres as $genre) {
            $dbGenre = $this->genreRepository->find($genre->getId());
            //if genre already exist in DB, we replace the one from de-normalizer by the "real" one
            //otherwise, doctrine will try to create a new genre and fail because it already exists.
            if ($dbGenre) {
                $movie->removeGenre($genre);
                $movie->addGenre($dbGenre);
            }
        }
    }
}
