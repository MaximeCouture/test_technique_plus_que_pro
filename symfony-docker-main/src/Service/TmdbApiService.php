<?php

namespace App\Service;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TmdbApiService
{
    const QUERY_PARAMETERS = ['language' => 'fr-FR'];
    const GET_ALL_GENRE_URI = '/genre/movie/list';
    const GET_MOVIE_DETAILS_URI = '/movie/%s';
    const GET_TRENDING_MOVIE_DAY_URI = '/trending/movie/day';
    const GET_TRENDING_MOVIE_WEEK_URI = '/trending/movie/week';

    private $apiBasePath;

    public function __construct(string $apiBasePath, private HttpClientInterface $tmdbClient)
    {
        $this->apiBasePath = $apiBasePath;
    }

    public function getGenres(bool $resultAsArray = false)
    {
        $uri = $this->apiBasePath . self::GET_ALL_GENRE_URI;
        $request = $this->tmdbClient->request(
            'GET',
            $uri,
            ['query' => self::QUERY_PARAMETERS]
        );

        if ($resultAsArray) {
            return $request->toArray();
        }
        return $request->getContent();
    }

    public function getMovieDetails(int $movieId, bool $resultAsArray = false)
    {
        $uri = $this->apiBasePath . sprintf(self::GET_MOVIE_DETAILS_URI, $movieId);
        $request = $this->tmdbClient->request(
            'GET',
            $uri,
            ['query' => self::QUERY_PARAMETERS]
        );

        if ($resultAsArray) {
            return $request->toArray();
        }
        return $request->getContent();
    }

    public function getTrendingMoviesDay(int $page = 1, bool $resultAsArray = false)
    {
        $uri = $this->apiBasePath . self::GET_TRENDING_MOVIE_DAY_URI;
        $request = $this->tmdbClient->request(
            'GET',
            $uri,
            ['query' => array_merge(self::QUERY_PARAMETERS, ['page' => $page])]
        );

        if ($resultAsArray) {
            return $request->toArray();
        }
        return $request->getContent();
    }

    public function getTrendingMoviesWeek(int $page = 1, bool $resultAsArray = false)
    {
        $uri = $this->apiBasePath . self::GET_TRENDING_MOVIE_WEEK_URI;
        $request = $this->tmdbClient->request(
            'GET',
            $uri,
            ['query' => array_merge(self::QUERY_PARAMETERS, ['page' => $page])]
        );

        if ($resultAsArray) {
            return $request->toArray();
        }
        return $request->getContent();
    }
}
