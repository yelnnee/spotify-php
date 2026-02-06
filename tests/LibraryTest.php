<?php
declare(strict_types=1);
namespace Spotify\Tests\Model;
use PHPUnit\Framework\TestCase;
use Spotify\Model\Library;
use Spotify\Model\Artist;
use Spotify\Model\Album;
use Spotify\Model\Track;
use Spotify\Model\Playlist;
class LibraryTest extends TestCase
{
    public function testLibrarySearch(): void
    {
        $library = new Library();

        $beatles = new Artist('The Beatles');
        $abbeyRoad = new Album('Abbey Road', 1969, $beatles);

        $track1 = new Track('Come Together', 259);
        $track2 = new Track('Something', 183);

        $abbeyRoad->addTrack($track1);
        $abbeyRoad->addTrack($track2);

        $library->addArtist($beatles);
        $library->addAlbum($abbeyRoad);
        $library->addTrack($track1);
        $library->addTrack($track2);

        $results = $library->search('Come');
        $this->assertCount(1, $results['tracks']);
        $this->assertEquals('Come Together', $results['tracks'][0]->getTitle());

        $results = $library->search('beatles');
        $this->assertCount(1, $results['artists']);

        $results = $library->search('abbey');
        $this->assertCount(1, $results['albums']);
    }

    public function testLibraryStatistics(): void
    {
        $library = new Library();

        // Créer 2 artistes
        $artist1 = new Artist('Artist 1');
        $artist2 = new Artist('Artist 2');

        // Créer 3 albums
        $album1 = new Album('Album 1', 2020, $artist1);
        $album2 = new Album('Album 2', 2021, $artist2);
        $album3 = new Album('Album 3', 2022, $artist1);

        // Créer 5 pistes
        $tracks = [];
        for ($i = 1; $i <= 5; $i++) {
            $track = new Track("Track $i", $i * 60);
            $track->setRating($i);
            $tracks[] = $track;
        }

        // Ajouter les pistes aux albums
        $album1->addTrack($tracks[0]);
        $album1->addTrack($tracks[1]);
        $album2->addTrack($tracks[2]);
        $album2->addTrack($tracks[3]);
        $album3->addTrack($tracks[4]);

        // Ajouter tout à la bibliothèque
        $library->addAlbum($album1);
        $library->addAlbum($album2);
        $library->addAlbum($album3);

        // Créer une playlist
        $playlist = new Playlist('My Playlist');
        $playlist->addTrack($tracks[0]);
        $playlist->addTrack($tracks[1]);
        $library->addPlaylist($playlist);

        $stats = $library->getStatistics();

        $this->assertEquals(2, $stats['artist_count']);
        $this->assertEquals(3, $stats['album_count']);
        $this->assertEquals(5, $stats['track_count']);
        $this->assertEquals(1, $stats['playlist_count']);
        $this->assertEquals(540, $stats['total_duration']); // (60+120+180+240+300) = 900
        $this->assertEquals(3.0, $stats['average_rating']); // (1+2+3+4+5)/5 = 3
    }

    public function testAddTrackAutomaticallyAddsAlbumAndArtist(): void
    {
        $library = new Library();

        $artist = new Artist('Test Artist');
        $album = new Album('Test Album', 2023, $artist);
        $track = new Track('Test Track', 180);

        $album->addTrack($track);
        $library->addTrack($track);

        $this->assertCount(1, $library->getArtists());
        $this->assertCount(1, $library->getAlbums());
        $this->assertCount(1, $library->getTracks());
    }

    public function testDuplicateEntriesNotAdded(): void
    {
        $library = new Library();

        $artist = new Artist('Artist');
        $album = new Album('Album', 2023, $artist);
        $track = new Track('Track', 180);

        $album->addTrack($track);

        $library->addArtist($artist);
        $library->addArtist($artist);

        $library->addAlbum($album);
        $library->addAlbum($album);

        $library->addTrack($track);
        $library->addTrack($track);

        $this->assertCount(1, $library->getArtists());
        $this->assertCount(1, $library->getAlbums());
        $this->assertCount(1, $library->getTracks());
    }

    public function testPlaylistManagement(): void
    {
        $library = new Library();

        $playlist1 = new Playlist('Workout');
        $playlist2 = new Playlist('Chill');

        $library->addPlaylist($playlist1);
        $library->addPlaylist($playlist2);

        $this->assertCount(2, $library->getPlaylists());
        $this->assertEquals('Workout', $library->getPlaylists()[0]->getName());
        $this->assertEquals('Chill', $library->getPlaylists()[1]->getName());
    }
}
