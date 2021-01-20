<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Utilities;

use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;

class SafeMethods implements SafeMethodsInterface
{
    public function json_encode($value, int $options = 0, int $depth = 512): string
    {
        return \Safe\json_encode(
            $value,
            $options,
            $depth
        );
    }

    public function sprintf(string $format, ...$params): string
    {
        return \Safe\sprintf(
            $format,
            $params
        );
    }
}
