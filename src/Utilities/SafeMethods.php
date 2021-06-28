<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Utilities;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\FilesystemException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\JsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\StringsException;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;
use function error_clear_last;
use function json_last_error;

/**
 * @codeCoverageIgnore
 */
class SafeMethods implements SafeMethodsInterface
{
    public function json_encode(
        $value,
        int $options = self::JSON_ENCODE_OPTIONS,
        int $depth = self::JSON_ENCODE_DEPTH
    ): string {
        error_clear_last();
        $result = \json_encode($value, $options, $depth);
        if (false === $result) {
            throw JsonException::createFromPhpError();
        }

        return $result;
    }

    public function json_decode(
        string $json,
        bool $assoc = false,
        int $depth = self::JSON_DECODE_DEPTH,
        int $options = self::JSON_DECODE_OPTIONS
    ) {
        $data = \json_decode($json, $assoc, $depth, $options);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonException::createFromPhpError();
        }

        return $data;
    }

    public function sprintf(string $format, ...$params): string
    {
        error_clear_last();
        if ([] !== $params) {
            $result = \sprintf($format, ...$params);
        } else {
            $result = \sprintf($format);
        }
        if (false === $result) {
            throw StringsException::createFromPhpError();
        }

        return $result;
    }

    public function file_put_contents(
        string $filename,
        $data,
        int $flags = self::FILE_PUT_CONTENTS_FLAGS,
        $context = null
    ): int {
        error_clear_last();
        if (null !== $context) {
            $result = \file_put_contents($filename, $data, $flags, $context);
        } else {
            $result = \file_put_contents($filename, $data, $flags);
        }
        if (false === $result) {
            throw FilesystemException::createFromPhpError();
        }

        return $result;
    }

    public function file_get_contents(
        string $filename,
        bool $use_include_path = false,
        $context = null,
        int $offset = 0,
        int $maxlen = null
    ): string {
        error_clear_last();
        if (null !== $maxlen) {
            $result = \file_get_contents($filename, $use_include_path, $context, $offset, $maxlen);
        } elseif (0 !== $offset) {
            $result = \file_get_contents($filename, $use_include_path, $context, $offset);
        } elseif (null !== $context) {
            $result = \file_get_contents($filename, $use_include_path, $context);
        } else {
            $result = \file_get_contents($filename, $use_include_path);
        }
        if (false === $result) {
            throw FilesystemException::createFromPhpError();
        }

        return $result;
    }
}
