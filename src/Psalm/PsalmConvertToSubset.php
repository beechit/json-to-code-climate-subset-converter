<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Psalm;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;

final class PsalmConvertToSubset extends AbstractConverter
{
    public function convertToSubset(): void
    {
        try {
            $this->abstractJsonValidator->validateJson();

            foreach ($this->json as $node) {
                $this->codeClimateNodes[] = [
                    'description' => $this->createDescription($node->message),
                    'fingerprint' => $this->createFingerprint(
                        $node->message,
                        $node->file_name,
                        $node->line_from
                    ),
                    'severity' => $this->getSeverity($node->severity),
                    'location' => [
                        'path' => $node->file_name,
                        'lines' => [
                            'begin' => $node->line_from,
                            'end' => $node->line_to,
                        ],
                    ],
                ];
            }
        } catch (InvalidJsonException $exception) {
            throw $exception;
        }
    }

    public function getToolName(): string
    {
        return 'Psalm';
    }

    private function getSeverity(string $severity): string
    {
        // can be info, minor, major, critical, or blocker
        return 'error' === $severity ? 'major' : 'minor';
    }
}
