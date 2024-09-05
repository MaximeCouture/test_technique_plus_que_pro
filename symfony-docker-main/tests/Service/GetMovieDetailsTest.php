<?php

namespace App\Tests\Service;


use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetMovieDetailsTest extends KernelTestCase
{

    /**
     * Check if 2 Movies are retrieved when trying to get 2 Movies
     */
    public function testGetMovieDetailsCount()
    {
        $movies = $this->getMovies(['1022789', '945475']);
        $this->assertCount(2, $movies);
    }

    /**
     * Check if the movie is properly hydrated
     */
    public function testGetMovieDetailsHydrated()
    {
        $movies = $this->getMovies(['1022789']);
        $movie = $movies[0];
        $this->assertEquals('1022789', $movie->getId());
    }

    /**
     * Check if the retrieved Movie has all its genres
     */
    public function testGetMovieDetailsGenresCount()
    {
        $movies = $this->getMovies(['1022789']);
        $movie = $movies[0];
        $this->assertCount('4', $movie->getGenres());
    }

    /**
     * Check if the retrieved Movie Genres are hydrated
     */
    public function testGetMovieDetailsGenresHydrated()
    {
        $movies = $this->getMovies(['1022789']);
        $movie = $movies[0];
        $genres = array_map(fn($genre) => $genre->getName(), $movie->getGenres()->toArray());
        $this->assertContains( "Aventure", $genres);
    }



    private function getMovies(array $moviesId): array
    {
        self::bootKernel();
        $container = static::getContainer();
        $helper = $container->get('App\Helper\Movie\GetMoviesHelper');
        return $helper->getMovies($moviesId);
    }
}
