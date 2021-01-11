<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

interface JsonValidatorInterface
{
    public function validateJson(): void;
}
