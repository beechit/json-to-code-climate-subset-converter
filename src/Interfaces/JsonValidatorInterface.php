<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

interface JsonValidatorInterface
{
    public function validateJson(): void;
}
