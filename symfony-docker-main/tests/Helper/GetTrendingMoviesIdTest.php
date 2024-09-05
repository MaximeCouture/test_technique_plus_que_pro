<?php

namespace App\Tests\Helper;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetTrendingMoviesIdTest extends KernelTestCase
{

    /**
     * Check if 2 Movies are retrieved when trying to get trending for the day
     */
    public function testGetDayTrendingMoviesIdCount()
    {
        $moviesId = $this->getTrendingMoviesId();
        $this->assertCount(2, $moviesId);
    }

    /**
     * Check if the id is properly retrieved for the day
     */
    public function testGetDayTrendingMoviesId()
    {
        $moviesId = $this->getTrendingMoviesId();
        $this->assertContains(945475, $moviesId);
    }

    /**
     * Check if 2 Movies are retrieved when trying to get trending for the wek
     */
    public function testGetWeekTrendingMoviesIdCount()
    {
        $moviesId = $this->getTrendingMoviesId(false);
        $this->assertCount(2, $moviesId);
    }

    /**
     * Check if the id is properly retrieved for the week
     */
    public function testGetWeekTrendingMoviesId()
    {
        $moviesId = $this->getTrendingMoviesId(false);
        $this->assertContains(1022789, $moviesId);
    }



    private function getTrendingMoviesId(bool $daily = true): array
    {
        self::bootKernel();
        $container = static::getContainer();
        $helper = $container->get('App\Helper\Movie\GetTrendingMoviesIdHelper');
        return $helper->getTrendingMoviesId($daily, 1);
    }
}
