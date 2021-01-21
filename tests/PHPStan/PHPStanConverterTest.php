<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPStan;

use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PHPStanConverterTest extends TestCase
{
    public function testItCanConvertPhpStanSuccesfulJsonToSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/empty.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPStan', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPStan',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();

        // Then
        $this->assertEquals([], $converterImplementation->getOutput());
    }

    public function testItCanConvertPhpStanJsonToSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPStan', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPStan',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();

        // Then
        $this->assertEquals(
            [
                [
                    'description' => '(PHPStan) Return type (array) of method App\Class::processNode() should be covariant with return type (array<PHPStan\Rules\RuleError|string>) of method PHPStan\Rules\Rule::processNode()',
                    'fingerprint' => '44fee3bc600b885c545139e2f5cfb49d',
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

    public function testItCanConvertPhpStanJsonToJsonSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__.'/fixtures/output.json');

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPStan', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPStan',
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
}
