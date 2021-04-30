<?php

namespace App\Service\UrlShortener;

use App\Entity\Url;

interface UrlShortenerInterface
{
    public function generateShortCode(Url $url): string;
}
