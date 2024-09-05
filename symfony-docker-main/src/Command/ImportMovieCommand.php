<?php

namespace App\Command;

use App\Entity\Movie;
use App\Helper\Movie\GetMoviesHelper;
use App\Helper\Movie\GetTrendingMoviesIdHelper;
use App\Helper\Movie\ReplaceGenreByDbGenreHelper;
use App\Repository\MovieRepository;
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
        private readonly EntityManagerInterface      $entityManager,
        private readonly MovieRepository             $movieRepository,
        private readonly GetTrendingMoviesIdHelper   $getTrendingMoviesIdHelper,
        private readonly GetMoviesHelper             $getMoviesHelper,
        private readonly ReplaceGenreByDbGenreHelper $replaceGenreByDbGenreHelper,
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
        $trendingMoviesIdDaily = $this->getTrendingMoviesIdHelper->getTrendingMoviesId(true, $page_limit);
        $output->writeln('<info>Fetching Trending movies for the week</info>');
        $trendingMoviesIdWeekly = $this->getTrendingMoviesIdHelper->getTrendingMoviesId(false, $page_limit);

        //we will also fetch all movies currently in DB to update their info, especially the trending info
        $dbMoviesId = $this->movieRepository->getAllMoviesId();
        //prevent fetching the same movie multiple times
        $moviesToImport = array_unique(array_merge($trendingMoviesIdDaily, $trendingMoviesIdWeekly, $dbMoviesId));
        $nbMovies = count($moviesToImport);
        $output->writeln('<info>Trends fetched, importing movies</info>');
        $output->writeln("<info>$nbMovies movie(s) to import</info>");

        $movies = $this->getMoviesHelper->getMovies($moviesToImport);

        $output->writeln('<info>All movies are fetched, adding trending information to each movie</info>');
        //adding trending order to each movie for day and week (monthly doesn't exist on API)
        foreach ($movies as $movie) {
            // add trending info to movie
            $this->updateMovieTrendingInfo($movie, $trendingMoviesIdDaily, $trendingMoviesIdWeekly);
            //prevent cascading issues
            $this->replaceGenreByDbGenreHelper->replaceGenreByDbGenre($movie);
            $this->entityManager->persist($movie);
            //@TODO the larger the movie pool, the more likely it will be needed to separate flushing in batch
        }

        $output->writeln('<info>Updating Database</info>');

        $this->entityManager->flush();

        $endTime = microtime(true);
        $totalTime = round($endTime - $startingTime, 3);
        $output->writeln("<info>$nbMovies movies imported succesfully in $totalTime seconds</info>");
        return 0;
    }

    /**
     * Set movie trending info based on index in array containing trending movies
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
        //prevent null when order = 0 ; eg movie is #1 trend
        $movie->setTrendingDayOrder($trendingDayOrder !== false ? $trendingDayOrder + 1 : null);
        $movie->setTrendingWeekOrder($trendingWeekOrder !== false ? $trendingWeekOrder + 1 : null);
    }
}
