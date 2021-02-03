<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Utilities;

use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;

class SafeMethods implements SafeMethodsInterface
{
    public function json_encode(
        $value,
        int $options = self::JSON_ENCODE_OPTIONS,
        int $depth = self::JSON_ENCODE_DEPTH
    ): string {
        return \Safe\json_encode(
            $value,
            $options,
            $depth
        );
    }

    public function json_decode(
        string $json,
        bool $assoc = false,
        int $depth = self::JSON_DECODE_DEPTH,
        int $options = self::JSON_DECODE_OPTIONS
    ) {
        return \Safe\json_decode($json, $assoc, $depth, $options);
    }

    public function sprintf(string $format, ...$params): string
    {
        return \Safe\sprintf(
            $format,
            ...$params
        );
    }

    public function file_put_contents(
        string $filename,
        $data,
        int $flags = self::FILE_PUT_CONTENTS_FLAGS,
        $context = null
    ): int {
        return \Safe\file_put_contents(
            $filename,
            $data,
            $flags,
            $context
        );
    }

    public function file_get_contents(string $filename): string
    {
        return \Safe\file_get_contents($filename);
    }
}
