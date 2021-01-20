<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;

interface SafeMethodsInterface
{
    /**
     * @param $value
     *
     * @throws JsonException
     */
    public function json_encode($value, int $options = 0, int $depth = 512): string;

    /**
     * @param string $format
     * @param mixed ...$params
     * @return string
     * @throws StringsException
     */
    public function sprintf(string $format, ...$params): string;
}
