<?php
declare(strict_types=1);
namespace Spotify\Tests\Model;
use PHPUnit\Framework\TestCase;
use Spotify\Model\Artist;
use Spotify\Model\Album;
class ArtistTest extends TestCase
{
    public function testArtistHasName(): void
    {
        $artist = new Artist('The Beatles');
        $this->assertEquals('The Beatles', $artist->getName());
    }
    public function testArtistCanHaveMultipleGenres(): void
    {
        $artist = new Artist('Radiohead');
        $artist->addGenre('Alternative Rock');
        $artist->addGenre('Art Rock');
        $this->assertCount(2, $artist->getGenres());
        $this->assertContains('Alternative Rock', $artist->getGenres());
        $this->assertContains('Art Rock', $artist->getGenres());
    }
    public function testArtistCanHaveAlbums(): void
    {
        $artist = new Artist('Pink Floyd');
        $album1 = new Album('The Dark Side of the Moon', 1973, $artist);
        $album2 = new Album('The Wall', 1979, $artist);
        $this->assertCount(2, $artist->getAlbums());
        $this->assertEquals('The Dark Side of the Moon', $artist->getAlbums()[0]->getTitle());
    }

    public function testArtistGetTotalTracks(): void
    {
        $artist = new Artist('Artist');
        $album = new Album('Album', 2020, $artist);

        $track1 = $this->createMock(\Spotify\Model\Track::class);
        $track2 = $this->createMock(\Spotify\Model\Track::class);

        $album->addTrack($track1);
        $album->addTrack($track2);

        $this->assertEquals(2, $artist->getTotalTracks());
    }

    public function testDuplicateGenresNotAdded(): void
    {
        $artist = new Artist('Artist');
        $artist->addGenre('Rock');
        $artist->addGenre('Rock');

        $this->assertCount(1, $artist->getGenres());
    }
}