<?php

declare(strict_types=1);

namespace App\Champion\Application\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ChampionValidationException extends \RuntimeException
{
    /** @param array<string, string[]> $failuresByRiotId */
    private function __construct(
        string $message,
        private readonly array $failuresByRiotId,
    ) {
        parent::__construct($message);
    }

    public static function forSingle(string $riotId, ConstraintViolationListInterface $violations): self
    {
        $messages = self::extractMessages($violations);

        return new self(
            sprintf('Champion "%s" validation failed: %s', $riotId, implode(', ', $messages)),
            [$riotId => $messages],
        );
    }

    /**
     * @param array<string, ConstraintViolationListInterface> $failuresByRiotId
     */
    public static function forBulk(array $failuresByRiotId): self
    {
        $summary = [];
        $formatted = [];

        foreach ($failuresByRiotId as $riotId => $violations) {
            $messages = self::extractMessages($violations);
            $formatted[$riotId] = $messages;
            $summary[] = sprintf('"%s" (%s)', $riotId, implode(', ', $messages));
        }

        return new self(
            sprintf('%d champion(s) failed validation: %s', count($formatted), implode(' | ', $summary)),
            $formatted,
        );
    }

    /** @return array<string, string[]> */
    public function failuresByRiotId(): array
    {
        return $this->failuresByRiotId;
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
