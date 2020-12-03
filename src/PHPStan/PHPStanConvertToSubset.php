<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\PHPStan;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;

final class PHPStanConvertToSubset extends AbstractConverter
{
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
                    'location' => [
                        'path' => $node->location->path,
                        'lines' => [
                            'begin' => $node->location->lines->begin,
                        ],
                    ],
                ];
            }
        } catch (InvalidJsonException $exception) {
            throw $exception;
        }
    }

    protected function getToolName(): string
    {
        return 'PHPStan';
    }
}
