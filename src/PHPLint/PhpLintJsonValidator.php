<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;

class PhpLintJsonValidator extends AbstractJsonValidator
{
    public function validateJson(): void
    {
        if (!property_exists($this->json, 'errors')) {
            throw new InvalidJsonException('The [errors] is a required property');
        }

        foreach ($this->json->errors as $node) {
            if (!property_exists($node, 'file')) {
                throw new InvalidJsonException('The [errors.file] is a required property');
            }

            if (!property_exists($node, 'error')) {
                throw new InvalidJsonException('The [errors.error] is a required property');
            }

            if (!property_exists($node, 'line')) {
                throw new InvalidJsonException('The [errors.line] is a required property');
            }
        }
    }
}
