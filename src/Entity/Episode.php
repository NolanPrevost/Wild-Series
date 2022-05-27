<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    private $season_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeasonId(): ?Season
    {
        return $this->season_id;
    }

    public function setSeasonId(?Season $season_id): self
    {
        $this->season_id = $season_id;

        return $this;
    }
}
