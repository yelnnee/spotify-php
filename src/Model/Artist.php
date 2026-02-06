<?php

declare(strict_types=1);

namespace Spotify\Model;

class Artist
{
    private string $name;
    private array $genres = [];
    private array $albums = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addGenre(string $genre): void
    {
        if (!in_array($genre, $this->genres, true)) {
            $this->genres[] = $genre;
        }
    }

    public function getGenres(): array
    {
        return $this->genres;
    }

    public function addAlbum(Album $album): void
    {
        if (!in_array($album, $this->albums, true)) {
            $this->albums[] = $album;
        }
    }

    public function getAlbums(): array
    {
        return $this->albums;
    }

    public function getTotalTracks(): int
    {
        $count = 0;
        foreach ($this->albums as $album) {
            $count += count($album->getTracks());
        }
        return $count;
    }
}

