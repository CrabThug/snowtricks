<?php


namespace App\Service;


class UrlExtract
{
    private $url;

    public function extract($embed)
    {
        if(filter_var($embed, FILTER_VALIDATE_URL)) {
            return $this->url = $embed;
        }

        if (preg_match('/src=.+?(")/', $embed, $result)) {
            $this->url = $result[0];
            $this->url = substr($this->url, 4);
            $this->url = trim($this->url, '"');
        }

        if (filter_var($this->url, FILTER_VALIDATE_URL)) {
            return $this->url;
        }
    }
}
