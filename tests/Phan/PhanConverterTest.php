<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Phan;

use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PhanConverterTest extends TestCase
{
    public function testItCanConvertPhanJsonToSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('Phan', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'Phan',
            $validator,
            $jsonDecodedInput
        );

        // When
        $converterImplementation->convertToSubset();

        // Then
        $this->assertEquals(
            [
                [
                    'description' => '(Phan) UndefError PhanUndeclaredClassConstant Reference to constant class from undeclared class \PhpParser\Node\Stmt\ClassMethod',
                    'fingerprint' => 'e8547906ee21b4f8e8804de980a9d239',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 32,
                            'end' => 34,
                        ],
                    ],
                ],
            ],
            $converterImplementation->getOutput()
        );
    }

    public function testItCanConvertPhanJsonToJsonSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__.'/fixtures/output.json');

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('Phan', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'Phan',
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
