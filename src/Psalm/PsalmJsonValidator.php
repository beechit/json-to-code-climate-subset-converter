<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Psalm;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;

class PsalmJsonValidator extends AbstractJsonValidator
{
    public function validateJson(): void
    {
        foreach ($this->json as $node) {
            if (!property_exists($node, 'message')) {
                throw new InvalidJsonException('The [message] is a required property');
            }

            if (!property_exists($node, 'file_name')) {
                throw new InvalidJsonException('The [file_name] is a required property');
            }

            if (!property_exists($node, 'line_from')) {
                throw new InvalidJsonException('The [line_from] is a required property');
            }

            if (!property_exists($node, 'line_to')) {
                throw new InvalidJsonException('The [line_to] is a required property');
            }
        }
    }
}
