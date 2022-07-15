<?php

namespace App\Services\API\LOL\DTO;

class ContentDto
{
    private string $locale;
    private string $content;

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return ContentDto
     */
    public function setLocale(string $locale): ContentDto
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return ContentDto
     */
    public function setContent(string $content): ContentDto
    {
        $this->content = $content;
        return $this;
    }
}