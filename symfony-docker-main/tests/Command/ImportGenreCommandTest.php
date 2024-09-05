<?php

namespace App\Tests\Command;

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportGenreCommandTest extends KernelTestCase
{

    public function testImportGenreCommand(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $application = new Application(self::$kernel);
        $command = $application->find('app:import-genre');
        $commandTester = new CommandTester($command);
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $genres = $entityManager->getRepository(Genre::class)->findAll();
        $this->assertCount(0, $genres);

        $commandTester->execute([]);
        $commandTester->assertCommandIsSuccessful();

        $genres = $entityManager->getRepository(Genre::class)->findAll();
        $this->assertCount(19, $genres);

        $genresName =  array_map(fn($genre) => $genre->getName(), $genres);
        $this->AssertContains("Action", $genresName);
    }
}
