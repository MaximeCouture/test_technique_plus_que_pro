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

    public function getMoviesDetails(mixed $movieIds, bool $resultAsArray = false)
    {
        $uris = [];
        $params = [];
        foreach (array_unique($movieIds) as $movieId) {
            $uris[] = $this->apiBasePath . sprintf(self::GET_MOVIE_DETAILS_URI, $movieId);
            $params[] = ['query' => self::QUERY_PARAMETERS];
        }

        return $this->getMultipleResponseAsynchronous($uris, $params, $resultAsArray);

    }

    public function getTrendingMovies(bool $daily = true, array $pages = [1], bool $resultAsArray = false)
    {
        $uris = [];
        $params = [];
        foreach ($pages as $page) {
            $uris[] = $this->apiBasePath . ($daily ? self::GET_TRENDING_MOVIE_DAY_URI : self::GET_TRENDING_MOVIE_WEEK_URI);
            $params[] = ['query' => array_merge(self::QUERY_PARAMETERS, ['page' => $page])];
        }

        return $this->getMultipleResponseAsynchronous($uris, $params, $resultAsArray);
    }

    /**
     * Allow asynchronous batch fetching for performance issues
     *
     * @param array $uris
     * @param array $params
     * @param bool $resultAsArray
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getMultipleResponseAsynchronous(array $uris, array $params, bool $resultAsArray = false)
    {
        $requests = [];
        $responses = [];
        foreach ($uris as $key => $uri) {
            $requests[] = $this->tmdbClient->request('GET', $uri, $params[$key]);
        }
        if ($resultAsArray) {
            foreach ($requests as $request) {
                $responses[] = $request->toArray();
            }
        }
        else {
            foreach ($requests as $request) {
                $responses[] = $request->getContent();
            }
        }

        return $responses;
    }
}
