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
                /**
                 * @see https://github.com/phpstan/phpstan/issues/2652
                 */
                $filename = str_replace('/var/www/html/', '', $node->location->path);

                $this->codeClimateNodes[] = [
                    'description' => $this->createDescription($node->description),
                    'fingerprint' => $this->createFingerprint(
                        $node->description,
                        $filename,
                        $node->location->lines->begin
                    ),
                    'location' => [
                        'path' => $filename,
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
