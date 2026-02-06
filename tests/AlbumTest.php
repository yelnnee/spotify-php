<?php
declare(strict_types=1);
namespace Spotify\Tests\Model;
use PHPUnit\Framework\TestCase;
use Spotify\Model\Album;
use Spotify\Model\Artist;
use Spotify\Model\Track;
class AlbumTest extends TestCase
{
    public function testAlbumCreation(): void
    {
        $artist = new Artist('The Beatles');
        $album = new Album('Abbey Road', 1969, $artist);

        $this->assertEquals('Abbey Road', $album->getTitle());
        $this->assertEquals(1969, $album->getYear());
        $this->assertSame($artist, $album->getArtist());
    }

    public function testAlbumTracks(): void
    {
        $artist = new Artist('The Beatles');
        $album = new Album('Abbey Road', 1969, $artist);

        $track1 = new Track('Come Together', 259);
        $track2 = new Track('Something', 183);

        $album->addTrack($track1);
 $album->addTrack($track2);

        $this->assertCount(2, $album->getTracks());
    }

    public function testAlbumDuration(): void
    {
        $artist = new Artist('Queen');
        $album = new Album('A Night at the Opera', 1975, $artist);

        $track1 = new Track('Death on Two Legs', 223);
        $track2 = new Track('Lazing on a Sunday Afternoon', 68);

        $album->addTrack($track1);
        $album->addTrack($track2);

        $this->assertEquals(291, $album->getDuration());
        $this->assertEquals('4:51', $album->getFormattedDuration());
    }

    public function testAlbumFormattedDurationWithHours(): void
    {
        $artist = new Artist('Pink Floyd');
        $album = new Album('The Wall', 1979, $artist);

        // Simuler une longue durée (plus d'une heure)
        $track = $this->createMock(Track::class);
        $track->method('getDuration')->willReturn(4000);

        $album->addTrack($track);

        $this->assertEquals('1:06:40', $album->getFormattedDuration());
    }
}
