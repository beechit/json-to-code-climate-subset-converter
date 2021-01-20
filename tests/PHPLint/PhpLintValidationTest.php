<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PhpLintValidationTest extends TestCase
{
    public function testItThrowsAnExceptionWhenErrorsPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-errors-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenErrorsFilePropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.file] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-errors-file-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenErrorsErrorPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.error] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-errors-error-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenErrorsLinePropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.line] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-errors-line-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }
}
