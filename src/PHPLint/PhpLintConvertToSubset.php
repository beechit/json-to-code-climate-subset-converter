<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\ConvertToSubsetInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;

final class PhpLintConvertToSubset extends AbstractConverter implements ConvertToSubsetInterface
{
    protected function getToolName(): string
    {
        return 'PHPLint';
    }

    public function convertToSubset(): void
    {
        try {
            $this->abstractJsonValidator->validateJson();

            foreach ($this->json->errors as $node) {
                /**
                 * @see https://github.com/overtrue/phplint/issues/63
                 */
                $filename = str_replace('/var/www/html/', '', $node->file);

                $this->codeClimateNodes[] = [
                    'description' => $this->createDescription($node->error),
                    'fingerprint' => $this->createFingerprint(
                        $node->error,
                        $filename,
                        $node->line
                    ),
                    'location' => [
                        'path' => $filename,
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
}
