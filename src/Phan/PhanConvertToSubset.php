<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Phan;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;

final class PhanConvertToSubset extends AbstractConverter
{
    public function convertToSubset(): void
    {
        try {
            $this->abstractJsonValidator->validateJson();

            foreach ($this->json as $node) {
                $this->codeClimateNodes[] = [
                    'description' => $this->createDescription((string) $node->description),
                    'fingerprint' => $this->createFingerprint(
                        (string) $node->description,
                        (string) $node->location->path,
                        (int) $node->location->lines->begin
                    ),
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
