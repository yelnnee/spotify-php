<?php
declare(strict_types=1);
namespace Spotify\Model;

class Library {
    private array $artists = [];
    private array $albums = [];
    private array $tracks = [];
    private array $playlists = [];

    public function addArtist(Artist $artist): void
    {
        if (!in_array($artist, $this->artists, true)) {
            $this->artists[] = $artist;
        }
    }

    public function getArtists(): array
    {
        return $this->artists;
    }

    public function addAlbum(Album $album): void
    {
        if (!in_array($album, $this->albums, true)) {
            $this->albums[] = $album;
            $this->addArtist($album->getArtist());
        }
    }

    public function getAlbums(): array
    {
        return $this->albums;
    }

    public function addTrack(Track $track): void
    {
        if (!in_array($track, $this->tracks, true)) {
            $this->tracks[] = $track;
            if ($track->getAlbum()) {
                $this->addAlbum($track->getAlbum());
            }
        }
    }

    public function getTracks(): array
    {
        return $this->tracks;
    }

    public function addPlaylist(Playlist $playlist): void
    {
        if (!in_array($playlist, $this->playlists, true)) {
            $this->playlists[] = $playlist;
        }
    }

    public function getPlaylists(): array
    {
        return $this->playlists;
    }
}

