<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Phan;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;

class PhanJsonValidator extends AbstractJsonValidator
{
    public function validateJson(): void
    {
        foreach ($this->json as $node) {
            if (!property_exists($node, 'description')) {
                throw new InvalidJsonException('The [description] is a required property');
            }

            if (!property_exists($node, 'location')) {
                throw new InvalidJsonException('The [location] is a required property');
            }

            if (!property_exists($node->location, 'path')) {
                throw new InvalidJsonException('The [location.path] is a required property');
            }

            if (!property_exists($node->location, 'lines')) {
                throw new InvalidJsonException('The [location.lines] is a required property');
            }

            if (!property_exists($node->location->lines, 'begin')) {
                throw new InvalidJsonException('The [location.lines.begin] is a required property');
            }
        }
    }
}
