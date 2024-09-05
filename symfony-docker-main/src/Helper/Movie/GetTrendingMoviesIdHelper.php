<?php

namespace App\Helper\Movie;

use App\Service\TmdbApiService;

class GetTrendingMoviesIdHelper
{
    public function __construct(
        private readonly TmdbApiService $tmdbApiService
    )
    {
    }

    /**
     * Async batch fetching all trending movies, them make a list of movies id ordered by trending order
     *
     * @param int $page_limit number of pages to import (5 will import pages 1,2,3,4 and 5)
     * @param bool $daily true = trending for the day, false trending for the week
     * @return int[]
     */
    public function getTrendingMoviesId(bool $daily = true, int $page_limit = 10): array
    {
        $movieIds = [];
        $pages = [];

        //array containing all pages that need to be fetched
        for ($i = 1; $i <= $page_limit; $i++) {
            $pages[] = $i;
        }

        $trendings = $this->tmdbApiService->getTrendingMovies($daily, $pages, true);

        foreach ($trendings as $trending) {
            foreach ($trending['results'] as $movie) {
                $movieIds[] = $movie['id'];
            }
        }

        return $movieIds;
    }

}
