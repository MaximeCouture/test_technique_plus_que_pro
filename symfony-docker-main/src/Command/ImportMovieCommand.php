<?php

namespace App\Command;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:import-movie',
    description: 'Imports trending movies from TMDB into Database',
    hidden: false,
)]
class ImportMovieCommand extends Command
{

    public function __construct(
        private readonly TmdbApiService         $tmdbApiService,
        private readonly SerializerInterface    $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly MovieRepository        $movieRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'page_limit',
                InputArgument::REQUIRED,
                'number of pages to import for both day and week trend (20 items per page). Please note that due to API calls limitation, this might take a while'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startingTime = microtime(true);
        $page_limit = $input->getArgument('page_limit');
        $output->writeln('<info>Importing movies</info>');

        $output->writeln('<info>Fetching Trending movies for the day</info>');
        $trendingMoviesIdDaily = $this->getTrendingMovieIds($page_limit);
        $output->writeln('<info>Fetching Trending movies for the week</info>');
        $trendingMoviesIdWeekly = $this->getTrendingMovieIds($page_limit, false);
        //prevent fetching the same movie multiple times
        $moviesToImport = array_unique(array_merge($trendingMoviesIdDaily, $trendingMoviesIdWeekly));
        $nbMovies = count($moviesToImport);
        $output->writeln('<info>Trends fetched, importing movies</info>');
        $output->writeln("<info>$nbMovies movie(s) to import</info>");

        $movies = $this->getMovies($moviesToImport);

        $output->writeln('<info>All movies are fetched, adding trending information to each movie</info>');
        //adding trending order to each movie for day and week (monthly doesn't exist on API)
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            // add trending info to movie
            $this->updateMovieTrendingInfo($movie, $trendingMoviesIdDaily, $trendingMoviesIdWeekly);
            //prevent cascading issues
            $this->setGenresFromDB($movie);
            $this->entityManager->persist($movie);
        }

        $output->writeln('<info>Updating Database</info>');

        $this->entityManager->flush();

        $endTime = microtime(true);
        $totalTime = round($endTime - $startingTime, 3);
        $output->writeln("<info>$nbMovies movies imported succesfully in $totalTime seconds</info>");
        return 0;
    }

    /**
     * Async batch fetching all trending movies, them make a list of movies id ordered by trending order
     *
     * @TODO create Helper to do this for reusability.
     *
     * @param int $page_limit
     * @param bool $daily
     * @return array
     */
    private function getTrendingMovieIds(int $page_limit, bool $daily = true): array
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

    /**
     * Batch load Movies from API, then load each Movie from DB to update them if they exist
     *
     * @TODO create Helper to do this for reusability.
     *
     * @param $moviesId
     * @return array
     */
    private function getMovies($moviesId)
    {
        $movies = [];
        $moviesResponses = $this->tmdbApiService->getMoviesDetails($moviesId, true);
        foreach ($moviesResponses as $movie) {
            $DbMovie = $this->movieRepository->find($movie['id']);
            if ($DbMovie) {
                $movies[] = $this->serializer->denormalize($movie, Movie::class, 'array', ['object_to_populate' => $DbMovie]);
            }
            else {
                $movies[] = $this->serializer->denormalize($movie, Movie::class);
            }
        }

        return $movies;
    }

    /**
     *
     *
     * @param $movie
     * @param $dailyTrendingIds
     * @param $weeklyTrendingIds
     * @return void
     */
    private function updateMovieTrendingInfo($movie, $dailyTrendingIds, $weeklyTrendingIds)
    {
        $trendingDayOrder = array_search($movie->getId(), $dailyTrendingIds);
        $trendingWeekOrder = array_search($movie->getId(), $weeklyTrendingIds);
        //prevent null when order = 0
        $movie->setTrendingDayOrder($trendingDayOrder!== false ? $trendingDayOrder + 1 : null);
        $movie->setTrendingWeekOrder($trendingWeekOrder!== false ? $trendingWeekOrder + 1 : null);
    }

    /**
     * Used to prevent cascading error when saving movies
     *
     * @TODO create Helper to do this for reusability.
     *
     * @param $movie
     * @return void
     */
    private function setGenresFromDB($movie)
    {
        $genres = $movie->getGenres();
        foreach ($genres as $genre) {
            $dbGenre = $this->entityManager->getRepository(Genre::class)->find($genre->getId());
            //if genre already exist in DB, we replace the one from de-normalizer by the "real" one
            //otherwise, doctrine will try to create a new genre and fail because it already exists.
            if($dbGenre){
                $movie->removeGenre($genre);
                $movie->addGenre($dbGenre);
            }
        }
    }
}
