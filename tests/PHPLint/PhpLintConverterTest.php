<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateDescription;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateFingerprint;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToGetJsonEncodedOutputException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\SafeMethods;
use function file_get_contents;
use function json_decode;
use LogicException;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;

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
            $converterImplementation->getOutput()
        );
    }

    public function testItCanConvertPhpLintJsonToJsonSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__.'/fixtures/output.json');

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

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converterImplementation->getJsonEncodedOutput()
        );
    }

    public function testItThrowsAnExceptionWhenConvertingPhpLintJsonToJsonSubsetFails(): void
    {
        $this->expectException(UnableToGetJsonEncodedOutputException::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('json_encode')
            ->willThrowException(new JsonException());

        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput,
            $safeMethods
        );

        // When
        $converterImplementation->getJsonEncodedOutput();
    }

    public function testItThrowsAnExceptionWhenItCanNotCreateAFingerprint(): void
    {
        $this->expectException(UnableToCreateFingerprint::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('sprintf')
            ->will(
                $this->onConsecutiveCalls(
                    '',
                    $this->throwException(new StringsException())
                )
            );

        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput,
            $safeMethods
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenItCanNotCreateADescription(): void
    {
        $this->expectException(UnableToCreateDescription::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('sprintf')
            ->will(
                $this->onConsecutiveCalls(
                    $this->throwException(new StringsException()),
                    ''
                )
            );

        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPLint',
            $validator,
            $jsonDecodedInput,
            $safeMethods
        );

        // When
        $converterImplementation->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenObjectIsNotBuiltViaFactory(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Converter was not built via it\'s factory');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $safeMethods = new SafeMethods();

        // When
        new PhpLintConvertToSubset(
            $validator,
            $jsonDecodedInput,
            $safeMethods
        );
    }
}
