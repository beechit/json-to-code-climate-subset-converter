<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use function file_get_contents;
use function json_decode;
use LogicException;

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

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenErrorsFilePropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.file] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-errors-file-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenErrorsErrorPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.error] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-errors-error-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenErrorsLinePropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [errors.line] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-errors-line-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenObjectIsNotBuiltViaFactory(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Validator was not built via it\'s factory');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        new PhpLintJsonValidator($jsonDecodedInput);
    }
}
