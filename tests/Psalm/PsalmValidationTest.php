<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Psalm;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PsalmValidationTest extends TestCase
{
    public function testItThrowsAnExceptionWhenDescriptionPropertyIsMissing()
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

    public function testItThrowsAnExceptionWhenFileNamePropertyIsMissing()
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

    public function testItThrowsAnExceptionWhenLineFromPropertyIsMissing()
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

    public function testItThrowsAnExceptionWhenLineToPropertyIsMissing()
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
}
