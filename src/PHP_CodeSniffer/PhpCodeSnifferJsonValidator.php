<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;

class PhpCodeSnifferJsonValidator extends AbstractJsonValidator
{
    public function validateJson(): void
    {
        if (!property_exists($this->json, 'files')) {
            throw new InvalidJsonException('The [files] is a required property');
        }

        foreach ($this->json->files as $file) {
            if (!property_exists($file, 'messages')) {
                throw new InvalidJsonException('The [files.messages] is a required property');
            }

            foreach ($file->messages as $node) {
                if (!property_exists($node, 'message')) {
                    throw new InvalidJsonException('The [files.messages.message] is a required property');
                }

                if (!property_exists($node, 'line')) {
                    throw new InvalidJsonException('The [files.messages.line] is a required property');
                }
            }
        }
    }
}
