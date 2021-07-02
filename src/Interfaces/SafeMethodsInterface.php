<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\FilesystemException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\JsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\StringsException;

interface SafeMethodsInterface
{
    const JSON_ENCODE_OPTIONS = 0;
    const JSON_ENCODE_DEPTH = 512;
    const JSON_DECODE_OPTIONS = 0;
    const JSON_DECODE_DEPTH = 512;
    const FILE_PUT_CONTENTS_FLAGS = 0;

    /**
     * @param mixed $value
     *
     * @throws JsonException
     */
    public function json_encode(
        $value,
        int $options = self::JSON_ENCODE_OPTIONS,
        int $depth = self::JSON_ENCODE_DEPTH
    ): string;

    /**
     * @throws JsonException
     *
     * @return mixed
     */
    public function json_decode(
        string $json,
        bool $assoc = false,
        int $depth = self::JSON_DECODE_DEPTH,
        int $options = self::JSON_DECODE_OPTIONS
    );

    /**
     * @param mixed ...$params
     *
     * @throws StringsException
     */
    public function sprintf(string $format, ...$params): string;

    /**
     * @param mixed $data
     * @param null  $context
     *
     * @throws FilesystemException
     */
    public function file_put_contents(
        string $filename,
        $data,
        int $flags = self::FILE_PUT_CONTENTS_FLAGS,
        $context = null
    ): int;

    /**
     * @param null $context
     *
     * @throws FilesystemException
     */
    public function file_get_contents(
        string $filename,
        bool $use_include_path = false,
        $context = null,
        int $offset = 0,
        int $maxlen = null
    ): string;
}
