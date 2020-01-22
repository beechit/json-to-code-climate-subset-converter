<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;

class PhpLintValidationTest extends TestCase
{
    public function test_it_throws_an_exception_when_errors_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-errors-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_errors_file_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.file] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-errors-file-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_errors_error_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.error] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-errors-error-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_errors_line_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.line] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-errors-line-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }
}
