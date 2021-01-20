<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;

interface ValidatorFactoryInterface
{
    public function build(
        string $converterName,
        $json
    ): AbstractJsonValidator;
}
