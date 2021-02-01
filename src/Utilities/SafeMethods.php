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

    public function json_decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        return \Safe\json_decode($json, $assoc, $depth, $options);
    }

    public function sprintf(string $format, ...$params): string
    {
        return \Safe\sprintf(
            $format,
            ...$params
        );
    }

    public function file_put_contents(string $filename, $data, int $flags = 0, $context = null): int
    {
        return \Safe\file_put_contents(
            $filename,
            $data,
            $flags,
            $context
        );
    }

    public function file_get_contents(string $filename, bool $use_include_path = false, $context = null, int $offset = 0, int $maxlen = null): string
    {
        return \Safe\file_get_contents(
            $filename,
            $use_include_path,
            $context,
            $offset,
            $maxlen
        );
    }
}
