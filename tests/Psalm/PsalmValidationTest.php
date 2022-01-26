<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Psalm;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use function file_get_contents;
use function json_decode;

/**
 * @internal
 */
class PsalmValidationTest extends TestCase
{
    public function testItThrowsAnExceptionWhenDescriptionPropertyIsMissing(): void
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [message] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-message-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('Psalm', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'Psalm',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenFileNamePropertyIsMissing(): void
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [file_name] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-file-name-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('Psalm', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'Psalm',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLineFromPropertyIsMissing(): void
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [line_from] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-line-from-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('Psalm', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'Psalm',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLineToPropertyIsMissing(): void
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [line_to] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-line-to-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('Psalm', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'Psalm',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenSeverityPropertyIsMissing(): void
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [severity] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-severity-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('Psalm', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'Psalm',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }
}
