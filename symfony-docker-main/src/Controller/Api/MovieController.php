<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/movie')]
class MovieController extends AbstractController
{

    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly SerializerInterface $serializer,
    )
    {}

    #[Route('/trending', name: 'app_movie_trending', methods: ['GET'])]
    public function trending(Request $request): Response
    {
        $daily = boolval($request->query->get('daily', true));
        $page = $request->query->get('page', 1);
        $itemsPerPage = $request->query->get('itemsPerPage', 10);
        $movies = $this->movieRepository->getTrendings($daily, $page, $itemsPerPage);

        return new JsonResponse(
            $this->serializer->serialize($movies, 'json', ['groups' => ['getMovie']]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            true
        );
    }

    #[Route('/search', name: 'app_movies_search', methods: ['GET'])]
    public function autocompleteSearch(Request $request): JsonResponse
    {
        $term = $request->query->get('term', '');
        $results = $this->movieRepository->autocompleteSearch($term);

        return new JsonResponse(
            $this->serializer->serialize($results, 'json', ['groups' => ['getMovie']]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            true
        );
    }

    #[Route('/{id}', name: 'app_movies_details', methods: ['GET'])]
    public function find(Movie $movie): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($movie, 'json', ['groups' => ['getMovie', 'getMovieFull']]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            true
        );
    }

    #[Route('/{id}', name: 'app_movies_delete', methods: ['DELETE'])]
    public function delete(Movie $movie, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($movie);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
