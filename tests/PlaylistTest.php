<?php
declare(strict_types=1);
namespace Spotify\Tests\Model;
use PHPUnit\Framework\TestCase;
use Spotify\Model\Playlist;
use Spotify\Model\Track;
use Spotify\Model\Album;
use Spotify\Model\Artist;
class PlaylistTest extends TestCase
{
    private function createTrack(string $title, int $duration, int $rating = 0): Track
    {
        $track = new Track($title, $duration);
        $track->setRating($rating);
        return $track;
    }

    private function createTrackWithAlbum(string $title, int $duration, int $rating = 0): Track
    {
        $artist = new Artist('Artist');
        $album = new Album('Album', 2020, $artist);
        $track = new Track($title, $duration);
        $track->setAlbum($album);
        $track->setRating($rating);
        return $track;
    }

    public function testPlaylistTotalDuration(): void
    {
        $playlist = new Playlist('Road Trip');

        $track1 = $this->createTrack('Song 1', 180);
        $track2 = $this->createTrack('Song 2', 240);

        $playlist->addTrack($track1);
        $playlist->addTrack($track2);

        $this->assertEquals(420, $playlist->getTotalDuration());
        $this->assertEquals('7:00', $playlist->getFormattedDuration());
    }

    public function testFilterTracksByRating(): void
    {
        $playlist = new Playlist('Best of');

        $track1 = $this->createTrack('Hit 1', 200, 5);
        $track2 = $this->createTrack('Hit 2', 180, 4);
        $track3 = $this->createTrack('OK 1', 220, 3);

        $playlist->addTrack($track1);
        $playlist->addTrack($track2);
        $playlist->addTrack($track3);

        $highRated = $playlist->filterByMinRating(4);
        $this->assertCount(2, $highRated);

        $topRated = $playlist->filterByMinRating(5);
        $this->assertCount(1, $topRated);
    }

    public function testShufflePlaylist(): void
    {
        $playlist = new Playlist('Random Mix');

        for ($i = 1; $i <= 10; $i++) {
            $playlist->addTrack($this->createTrack("Track $i", 180));
        }

        $originalOrder = $playlist->getTracks();
        $playlist->shuffle();
        $shuffledOrder = $playlist->getTracks();

        // Vérifier que tous les éléments sont toujours présents
        $this->assertCount(10, $shuffledOrder);

        // Il y a une très faible chance que l'ordre soit identique (1/10!)
        // On peut vérifier que les tableaux ne sont pas identiques
        $identical = true;
        for ($i = 0; $i < 10; $i++) {
            if ($originalOrder[$i] !== $shuffledOrder[$i]) {
                $identical = false;
                break;
            }
        }
        $this->assertFalse($identical, 'Les playlists ne devraient pas être dans le même ordre après
        shuffle');
    }

    public function testPlaylistAverageRating(): void
    {
        $playlist = new Playlist('Rated Tracks');

        $track1 = $this->createTrack('Track 1', 180, 5);
        $track2 = $this->createTrack('Track 2', 200, 4);
        $track3 = $this->createTrack('Track 3', 220, 3);

        $playlist->addTrack($track1);
        $playlist->addTrack($track2);
        $playlist->addTrack($track3);

        $this->assertEquals(4.0, $playlist->getAverageRating());
    }

    public function testFilterByMaxDuration(): void
    {
        $playlist = new Playlist('Short Tracks');

        $track1 = $this->createTrack('Short', 120);
        $track2 = $this->createTrack('Medium', 240);
        $track3 = $this->createTrack('Long', 360);

        $playlist->addTrack($track1);
        $playlist->addTrack($track2);
        $playlist->addTrack($track3);

        $shortTracks = $playlist->filterByMaxDuration(180);
        $this->assertCount(1, $shortTracks);

        $mediumTracks = $playlist->filterByMaxDuration(300);
        $this->assertCount(2, $mediumTracks);
    }

    public function testRemoveTrack(): void
    {
        $playlist = new Playlist('Test');
        $track1 = $this->createTrack('Track 1', 180);
        $track2 = $this->createTrack('Track 2', 200);

        $playlist->addTrack($track1);
        $playlist->addTrack($track2);

        $this->assertCount(2, $playlist->getTracks());

        $playlist->removeTrack($track1);
        $this->assertCount(1, $playlist->getTracks());
        $this->assertSame($track2, $playlist->getTracks()[0]);
    }
}