<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Exceptions;

/**
 * @codeCoverageIgnore
 */
class StringsException extends \ErrorException
{
    public static function createFromPhpError(): self
    {
        $error = error_get_last();

        return new self($error['message'] ?? 'An error occured', 0, $error['type'] ?? 1);
    }
}
