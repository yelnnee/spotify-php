<?php
declare(strict_types=1);

namespace Spotify\Model;

class Album {
    private string $title;
    private int $year;
    private Artist $artist;
    private array $tracks = [];

    public function __construct(string $title, int $year, Artist $artist)
    {
        $this->title = $title;
        $this->year = $year;
        $this->artist = $artist;
        $artist->addAlbum($this);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getArtist(): Artist
    {
        return $this->artist;
    }

    public function addTrack(Track $track): void
    {
        if (!in_array($track, $this->tracks, true)) {
            $this->tracks[] = $track;
        }
    }

    public function getTracks(): array
    {
        return $this->tracks;
    }

    public function getDuration(): int
    {
        return array_sum(array_map(fn(Track $track) => $track->getDuration(), $this->tracks));
    }

    public function getFormattedDuration(): string
    {
        $duration = $this->getDuration();
        if ($duration >= 3600) {
            return sprintf('%d:%02d:%02d', floor($duration / 3600), floor(($duration % 3600) / 60), $duration % 60);
        }
        return sprintf('%d:%02d', floor($duration / 60), $duration % 60);
    }
}

