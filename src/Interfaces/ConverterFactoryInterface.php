<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;

interface ConverterFactoryInterface
{
    public function build(
        string $converterName,
        AbstractJsonValidator $validator,
        $json,
        SafeMethodsInterface $safeMethods = null
    ): AbstractConverter;
}
