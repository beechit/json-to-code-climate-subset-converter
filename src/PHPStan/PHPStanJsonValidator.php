<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\PHPStan;

use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\JsonValidatorInterface;

class PHPStanJsonValidator extends AbstractJsonValidator implements JsonValidatorInterface
{
    public function validateJson(): void
    {
        foreach ($this->json as $node) {
            if (!property_exists($node, 'description')) {
                throw new InvalidJsonException("The [description] is a required property");
            }

            if (!property_exists($node, 'location')) {
                throw new InvalidJsonException("The [location] is a required property");
            }

            if (!property_exists($node->location, 'path')) {
                throw new InvalidJsonException("The [location.path] is a required property");
            }

            if (!property_exists($node->location, 'lines')) {
                throw new InvalidJsonException("The [location.lines] is a required property");
            }

            if (!property_exists($node->location->lines, 'begin')) {
                throw new InvalidJsonException("The [location.lines.begin] is a required property");
            }
        }
    }
}
