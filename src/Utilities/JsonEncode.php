<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Utilities;

use Safe\Exceptions\JsonException;
use function Safe\json_encode;

trait JsonEncode
{
    /**
     * @param $value
     * @param int $options
     * @param int $depth
     * @return string
     * @throws JsonException
     */
    public function jsonEncode($value, int $options = 0, int $depth = 512): string
    {
        return json_encode(
            $value,
            $options,
            $depth
        );
    }
}
