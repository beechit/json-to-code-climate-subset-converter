<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

use Safe\Exceptions\FilesystemException;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;

interface SafeMethodsInterface
{
    const JSON_ENCODE_OPTIONS = 0;
    const JSON_ENCODE_DEPTH = 512;
    const JSON_DECODE_OPTIONS = 0;
    const JSON_DECODE_DEPTH = 512;
    const FILE_PUT_CONTENTS_FLAGS = 0;

    /**
     * @param $value
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
     *
     * @return mixed
     */
    public function file_put_contents(
        string $filename,
        $data,
        int $flags = self::FILE_PUT_CONTENTS_FLAGS,
        $context = null
    );

    /**
     * @return mixed
     */
    public function file_get_contents(string $filename);
}
