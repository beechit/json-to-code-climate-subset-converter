<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Phan;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;

final class PhanConvertToSubset extends AbstractConverter
{
    private const SEVERITY_LEVELS = [
        0 => 'info',
        5 => 'minor',
        10 => 'critical'
    ];
    
    public function convertToSubset(): void
    {
        try {
            $this->abstractJsonValidator->validateJson();

            foreach ($this->json as $node) {
                $this->codeClimateNodes[] = [
                    'description' => $this->createDescription($node->description),
                    'fingerprint' => $this->createFingerprint(
                        $node->description,
                        $node->location->path,
                        $node->location->lines->begin
                    ),
                    'severity' => self::SEVERITY_LEVELS[$node->severity] ?? null,
                    'location' => [
                        'path' => $node->location->path,
                        'lines' => [
                            'begin' => $node->location->lines->begin,
                            'end' => $node->location->lines->end,
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
        return 'Phan';
    }
}
