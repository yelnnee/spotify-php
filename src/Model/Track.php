<?php
declare(strict_types=1);

namespace Spotify\Model;

class Track {
    private string $title;
    private int $duration; // NB : en secondes
    private ?Album $album = null;
    private int $rating = 0; // Note de 0 à 5

    public function __construct(string $title, int $duration)
    {
        $this->title = $title;
        $this->duration = $duration;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getFormattedDuration(): string
    {
        return sprintf('%d:%02d', floor($this->duration / 60), $this->duration % 60);
    }

    public function setAlbum(Album $album): void
    {
        $this->album = $album;
        if (!in_array($this, $album->getTracks(), true)) {
            $album->addTrack($this);
        }
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function getArtist(): ?Artist
    {
        return $this->album ? $this->album->getArtist() : null;
    }

    public function setRating(int $rating): void
    {
        if ($rating < 0 || $rating > 5) {
            throw new \InvalidArgumentException('Rating must be between 0 and 5.');
        }
        $this->rating = $rating;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
}
