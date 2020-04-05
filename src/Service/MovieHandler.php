<?php


namespace App\Service;


use App\Entity\Movie;
use App\Entity\Trick;

class MovieHandler
{
    public function handle(Movie $movie, Trick $trick)
    {
        if (!filter_var($movie->getEmbed(), FILTER_VALIDATE_URL)) {
            $movie->setEmbed($this->extractUrl($movie->getEmbed()));
        }
        $movie->setTrick($trick);
    }

    public function extractUrl($embed)
    {
        preg_match('/src=.+?(")/', $embed, $result);
        $embed = $result[0];
        $embed = substr($embed, 4);
        $embed = trim($embed, '"');
        return $embed;
    }
}
