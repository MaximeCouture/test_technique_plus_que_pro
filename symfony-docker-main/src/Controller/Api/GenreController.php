<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/genre')]
class GenreController extends AbstractController
{
    public function __construct(
        private readonly GenreRepository $genreRepository,
        private readonly SerializerInterface $serializer
    )
    {}

    #[Route('', name: 'app_genre_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($this->genreRepository->findAll(), 'json', ['groups' => 'getGenre']),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            true
        );
    }

    #[Route('/{id}', name: 'app_genre_get', methods: ['GET'])]
    public function get(Genre $genre): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($genre, 'json', ['groups' => 'getGenre']),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            true
        );
    }
}
