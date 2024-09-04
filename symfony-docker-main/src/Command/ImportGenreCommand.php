<?php

namespace App\Command;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Service\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:import-genre',
    description: 'Imports all genres from TMDB into Database',
    hidden: false,
)]
class ImportGenreCommand extends Command
{

    public function __construct(
        private readonly TmdbApiService         $tmdbApiService,
        private readonly SerializerInterface    $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly GenreRepository        $genreRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Importing genres</info>');
        $response = $this->tmdbApiService->getGenres(true);
        if (
            !array_key_exists('genres', $response) ||
            !is_array($response['genres']) ||
            !count($response['genres'])
        ) {
            $output->writeln('<error>No genres found, aborting !</error>');
            return 1;
        }
        foreach ($response['genres'] as $genre) {
            $genre = $this->serializer->denormalize($genre, Genre::class);
            $genreId = $genre->getId();
            $dbGenre = $this->genreRepository->findOneBy(['id' => $genreId]);
            if (!$dbGenre) {
                $output->writeln("<info>Genre with ID $genreId doesn't exist, creating new entry</info>");
                $this->entityManager->persist($genre);
            } else {
                $output->writeln("<info>Genre with ID $genreId exist, updating</info>");
                $dbGenre->setName($genre->getName());
            }
        }
        $this->entityManager->flush();
        $output->writeln('<info>All done!</info>');

        return 0;
    }
}
