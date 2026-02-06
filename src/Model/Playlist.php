<?php
declare(strict_types=1);

namespace Spotify\Model;

class Playlist {
    private string $name;
    private array $tracks = [];
    public function __construct(string $name)
    {
        $this->name = $name;
    }


}
