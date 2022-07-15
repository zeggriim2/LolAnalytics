<?php

namespace App\Services\API\LOL\DTO;

class UpdateDto
{
    private int $id;
    private string $author;
    private bool $publish;
    /**
     * @var array<string>
     */
    private array $publish_locations;
    /**
     * @var ContentDto[]
     */
    private array $translations;
    private string $created_at;
    private string $updated_at;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UpdateDto
     */
    public function setId(int $id): UpdateDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return UpdateDto
     */
    public function setAuthor(string $author): UpdateDto
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublish(): bool
    {
        return $this->publish;
    }

    /**
     * @param bool $publish
     * @return UpdateDto
     */
    public function setPublish(bool $publish): UpdateDto
    {
        $this->publish = $publish;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getPublishLocations(): array
    {
        return $this->publish_locations;
    }

    /**
     * @param string[] $publish_locations
     * @return UpdateDto
     */
    public function setPublishLocations(array $publish_locations): UpdateDto
    {
        $this->publish_locations = $publish_locations;
        return $this;
    }

    /**
     * @return ContentDto[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param ContentDto[] $translations
     * @return UpdateDto
     */
    public function setTranslations(array $translations): UpdateDto
    {
        $this->translations = $translations;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return UpdateDto
     */
    public function setCreatedAt(string $created_at): UpdateDto
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     * @return UpdateDto
     */
    public function setUpdatedAt(string $updated_at): UpdateDto
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}