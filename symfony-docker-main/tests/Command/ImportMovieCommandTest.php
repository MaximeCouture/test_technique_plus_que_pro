<?php

namespace App\Tests\Command;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportMovieCommandTest extends KernelTestCase
{
    public function testImportMovieCommand()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $application = new Application(self::$kernel);
        $command = $application->find('app:import-movie');
        $commandTester = new CommandTester($command);
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $movies = $entityManager->getRepository(Movie::class)->findAll();
        $this->assertEmpty($movies);

        $commandTester->execute(["page_limit" => 1]);
        $commandTester->assertCommandIsSuccessful();

        $movies = $entityManager->getRepository(Movie::class)->findAll();
        $this->assertCount(3, $movies);

        $moviesTitle =  array_map(fn($movie) => $movie->getTitle(), $movies);
        $this->assertContains("Trap", $moviesTitle);
    }
}
