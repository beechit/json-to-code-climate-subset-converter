<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHP_CodeSniffer;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PhpCodeSnifferValidationTest extends TestCase
{
    public function testItThrowsAnExceptionWhenFilesPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-files-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHP_CodeSniffer', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHP_CodeSniffer',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenFilesMessagesPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files.messages] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-files-messages-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHP_CodeSniffer', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHP_CodeSniffer',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenFilesMessagesMessagePropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files.messages.message] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-files-messages-message-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHP_CodeSniffer', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHP_CodeSniffer',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenFilesMessagesLinePropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files.messages.line] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-files-messages-line-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHP_CodeSniffer', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHP_CodeSniffer',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();
    }
}
