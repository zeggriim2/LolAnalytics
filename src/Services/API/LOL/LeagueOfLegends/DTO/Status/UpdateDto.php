<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Status;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isPublish(): bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): self
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
     */
    public function setPublishLocations(array $publish_locations): self
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
     */
    public function setTranslations(array $translations): self
    {
        $this->translations = $translations;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(string $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
