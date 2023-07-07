<?php

declare(strict_types=1);

namespace App\Services\API\LOL\Helper;

class UrlHelper
{
    /**
     * @param array<string,string> $params
     */
    public static function constructUrl(string $url, array $params): string
    {
        foreach ($params as $key => $param) {
            $url = str_replace("{{$key}}", $param, $url);
        }

        return $url;
    }
}
