<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateFingerprint;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToGetJsonEncodedOutputException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\SafeMethods;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
use function file_get_contents;
use function json_decode;

/**
 * @internal
 */
class PhpLintConverterTest extends TestCase
{
    public function testItCanConvertPhpLintJsonToSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            [
                [
                    'description' => "(PHPLint) unexpected 'public' (T_PUBLIC), expecting ',' or ';' in line 2",
                    'fingerprint' => '9c0b73852026abfb670dd243d3b3c8f1',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 2,
                        ],
                    ],
                ],
            ],
            $converter->getOutput()
        );
    }

    public function testItCanConvertPhpLintJsonToJsonSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__.'/fixtures/output.json');

        $validatorFactory = new ValidatorFactory();

        /**
         * @var AbstractJsonValidator
         */
        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        /**
         * AbstractConverter $converterImplementation.
         */
        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converterImplementation->getJsonEncodedOutput()
        );
    }

    public function testItCanThrowAnExceptionWhenConvertingPhpLintJsonToJsonSubsetFails(): void
    {
        $this->expectException(UnableToGetJsonEncodedOutputException::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('json_encode')
            ->willThrowException(new JsonException());

        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        /**
         * @var AbstractJsonValidator
         */
        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        /**
         * AbstractConverter $converterImplementation.
         */
        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput,
            $safeMethods
        );

        // When
        $converterImplementation->getJsonEncodedOutput();
    }

    public function testItCanThrowAnExceptionWhenItCanNotCreateAFingerprints(): void
    {
        $this->expectException(UnableToCreateFingerprint::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('sprintf')
            ->willThrowException(new StringsException());

        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        /**
         * @var AbstractJsonValidator
         */
        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        /**
         * AbstractConverter $converterImplementation.
         */
        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput,
            $safeMethods
        );

        // When
        $converterImplementation->convertToSubset();
    }
}
