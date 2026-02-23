<?php

declare(strict_types=1);

namespace App\Summoner\Application\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class SummonerValidationException extends \RuntimeException
{
    /** @param array<string, string[]> $failuresByIdentifier */
    private function __construct(
        string $message,
        private readonly array $failuresByIdentifier,
    ) {
        parent::__construct($message);
    }

    public static function forSingle(string $identifier, ConstraintViolationListInterface $violations): self
    {
        $messages = self::extractMessages($violations);

        return new self(
            sprintf('Summoner "%s" validation failed: %s', $identifier, implode(', ', $messages)),
            [$identifier => $messages],
        );
    }

    /** @return array<string, string[]> */
    public function failuresByIdentifier(): array
    {
        return $this->failuresByIdentifier;
    }

    /** @return string[] */
    private static function extractMessages(ConstraintViolationListInterface $violations): array
    {
        $messages = [];

        foreach ($violations as $violation) {
            $messages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }

        return $messages;
    }
}
