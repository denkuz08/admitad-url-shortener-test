<?php

namespace App\Service\UrlShortener;

use App\Entity\Url;
use App\Service\UrlShortener\Exception\UrlShortenerException;

class IdUrlShortener extends AbstractUrlShortener
{
    public function generateShortCode(Url $url): string
    {
        if (empty($url->getId())) {
            throw new UrlShortenerException('Can not make short code');
        }

        return $this->convertIntToShortCode($url->getId());
    }
}
