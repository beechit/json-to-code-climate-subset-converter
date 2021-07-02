<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Utilities;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\FilesystemException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\JsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\StringsException;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;
use function error_clear_last;
use function error_get_last;
use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use function sprintf;

/**
 * @codeCoverageIgnore
 */
class SafeMethods implements SafeMethodsInterface
{
    /**
     * @param mixed $value
     *
     * @throws JsonException
     */
    public function json_encode(
        $value,
        int $options = self::JSON_ENCODE_OPTIONS,
        int $depth = self::JSON_ENCODE_DEPTH
    ): string {
        error_clear_last();

        $result = json_encode($value, $options, $depth);

        if (false === $result) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $result;
    }

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
    ) {
        $data = json_decode($json, $assoc, $depth, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException(
                json_last_error_msg(),
                json_last_error()
            );
        }

        return $data;
    }

    /**
     * @param mixed ...$params
     *
     * @throws StringsException
     */
    public function sprintf(string $format, ...$params): string
    {
        error_clear_last();

        $result = $this->nativeSprintf($params, $format);

        if (false === $result) {
            $error = error_get_last();

            throw new StringsException(
                $error['message'] ?? 'An error occured',
                0,
                $error['type'] ?? 1
            );
        }

        return $result;
    }

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
    ): int {
        error_clear_last();

        $result = $this->nativeFilePutContents($context, $filename, $data, $flags);

        if (false === $result) {
            $error = error_get_last();

            throw new FileSystemException(
                $error['message'] ?? 'An error occured',
                0,
                $error['type'] ?? 1
            );
        }

        return $result;
    }

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
    ): string {
        error_clear_last();

        $result = $this->nativeFileGetContents(
            $maxlen,
            $filename,
            $use_include_path,
            $context,
            $offset
        );

        if (false === $result) {
            $error = error_get_last();

            throw new FileSystemException(
                $error['message'] ?? 'An error occured',
                0,
                $error['type'] ?? 1
            );
        }

        return $result;
    }

    private function nativeSprintf(array $params, string $format): string
    {
        if ([] !== $params) {
            $result = sprintf($format, ...$params);
        } else {
            $result = sprintf($format);
        }

        return $result;
    }

    /**
     * @param $context
     * @param $data
     *
     * @return false|int
     */
    private function nativeFilePutContents($context, string $filename, $data, int $flags)
    {
        if (null !== $context) {
            $result = file_put_contents($filename, $data, $flags, $context);
        } else {
            $result = file_put_contents($filename, $data, $flags);
        }

        return $result;
    }

    /**
     * @param $context
     *
     * @return false|string
     */
    private function nativeFileGetContents(
        ?int $maxlen,
        string $filename,
        bool $use_include_path,
        $context,
        int $offset
    ) {
        if (null !== $maxlen) {
            $result = file_get_contents($filename, $use_include_path, $context, $offset, $maxlen);
        } elseif (0 !== $offset) {
            $result = file_get_contents($filename, $use_include_path, $context, $offset);
        } elseif (null !== $context) {
            $result = file_get_contents($filename, $use_include_path, $context);
        } else {
            $result = file_get_contents($filename, $use_include_path);
        }

        return $result;
    }
}
