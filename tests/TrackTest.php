<?php
declare(strict_types=1);
namespace Spotify\Tests\Model;
use PHPUnit\Framework\TestCase;
use Spotify\Model\Track;
use Spotify\Model\Album;
use Spotify\Model\Artist;
class TrackTest extends TestCase
{
    public function testTrackHasDurationInSeconds(): void
    {
        $track = new Track('Bohemian Rhapsody', 354);

        $this->assertEquals(354, $track->getDuration());
        $this->assertEquals('5:54', $track->getFormattedDuration());
    }
    public function testTrackRating(): void
    {
        $track = new Track('Imagine', 183);
        $this->assertEquals(0, $track->getRating());
        $track->setRating(4);
        $this->assertEquals(4, $track->getRating());
        $this->expectException(\InvalidArgumentException::class);
        $track->setRating(6); // Doit échouer
    }
    public function testTrackAlbumAssociation(): void
    {
        $artist = new Artist('The Beatles');
        $album = new Album('Abbey Road', 1969, $artist);
        $track = new Track('Come Together', 259);
        $track->setAlbum($album);
        $this->assertSame($album, $track->getAlbum());
        $this->assertSame($artist, $track->getArtist());
    }
    public function testTrackFormattedDurationEdgeCases(): void
    {
        $track1 = new Track('Short Track', 45);
        $this->assertEquals('0:45', $track1->getFormattedDuration());
        $track2 = new Track('Long Track', 3672);
        $this->assertEquals('61:12', $track2->getFormattedDuration());
    }
}