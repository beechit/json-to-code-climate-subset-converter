<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

use Safe\Exceptions\FilesystemException;
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
     * @throws JsonException
     *
     * @return mixed
     */
    public function json_decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0);

    /**
     * @param mixed ...$params
     *
     * @throws StringsException
     */
    public function sprintf(string $format, ...$params): string;

    /**
     * @param $data
     * @param null $context
     *
     * @throws FilesystemException
     */
    public function file_put_contents(string $filename, $data, int $flags = 0, $context = null): int;

    /**
     * @param null $context
     *
     * @throws FilesystemException
     */
    public function file_get_contents(string $filename, bool $use_include_path = false, $context = null, int $offset = 0, int $maxlen = null): string;
}
