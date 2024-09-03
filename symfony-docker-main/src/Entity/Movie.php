<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $overview = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster_path = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backdrop_path = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $release_date = null;

    #[ORM\Column(type: Types::SMALLFLOAT)]
    private ?float $vote_average = null;

    #[ORM\Column]
    private ?int $vote_count = null;

    #[ORM\Column(nullable: true)]
    private ?int $budget = null;

    //Avenger Endgame broke the regular int size (some might have done it before, but they weren't as high in trending ATM)
    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $revenue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tagline = null;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, cascade: ['persist'])]
    private Collection $genres;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $trending_day_order = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $trending_week_order = null;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): static
    {
        $this->overview = $overview;

        return $this;
    }

    public function getPosterPath(): ?string
    {
        return $this->poster_path;
    }

    public function setPosterPath(?string $poster_path): static
    {
        $this->poster_path = $poster_path;

        return $this;
    }

    public function getBackdropPath(): ?string
    {
        return $this->backdrop_path;
    }

    public function setBackdropPath(?string $backdrop_path): static
    {
        $this->backdrop_path = $backdrop_path;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(?\DateTimeInterface $release_date): static
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getVoteAverage(): ?float
    {
        return $this->vote_average;
    }

    public function setVoteAverage(?float $vote_average): static
    {
        $this->vote_average = $vote_average;

        return $this;
    }

    public function getVoteCount(): ?int
    {
        return $this->vote_count;
    }

    public function setVoteCount(int $vote_count): static
    {
        $this->vote_count = $vote_count;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(?int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getRevenue(): ?int
    {
        return $this->revenue;
    }

    public function setRevenue(?int $revenue): static
    {
        $this->revenue = $revenue;

        return $this;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(?string $tagline): static
    {
        $this->tagline = $tagline;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function getTrendingDayOrder(): ?int
    {
        return $this->trending_day_order;
    }

    public function setTrendingDayOrder(?int $trending_day_order): static
    {
        $this->trending_day_order = $trending_day_order;

        return $this;
    }

    public function getTrendingWeekOrder(): ?int
    {
        return $this->trending_week_order;
    }

    public function setTrendingWeekOrder(?int $trending_week_order): static
    {
        $this->trending_week_order = $trending_week_order;

        return $this;
    }
}
