<?php

namespace App\Service\UrlShortener;

abstract class AbstractUrlShortener implements UrlShortenerInterface
{
    protected const CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    protected function convertIntToShortCode(int $id): string
    {
        $charsLen = strlen(static::CHARS);
        $shortCode = '';

        $currentNumber = $id;
        while ($currentNumber) {
            $shortCode = static::CHARS[($currentNumber - 1) % $charsLen] . $shortCode;
            $currentNumber = intdiv($currentNumber - 1, $charsLen);
        }

        return $shortCode;
    }
}
