<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;

class PhpLintConvertToSubset extends AbstractConverter
{
    public function convertToSubset(): void
    {
        try {
            $this->abstractJsonValidator->validateJson();

            foreach ($this->json->errors as $node) {
                $this->codeClimateNodes[] = [
                    'description' => $this->createDescription($node->error),
                    'fingerprint' => $this->createFingerprint(
                        $node->error,
                        $node->file_name,
                        $node->line
                    ),
                    'location' => [
                        'path' => $node->file_name,
                        'lines' => [
                            'begin' => $node->line,
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
        return 'PHPLint';
    }
}
