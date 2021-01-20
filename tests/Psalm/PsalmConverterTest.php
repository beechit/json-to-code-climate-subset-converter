<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Psalm;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PsalmConverterTest extends TestCase
{
    public function testItCanConvertPsalmJsonToSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PsalmJsonValidator($jsonDecodedInput);
        $converter = new PsalmConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            [
                [
                    'description' => '(Psalm) Property Illuminate\\Foundation\\Console\\Kernel::$artisan is not defined in constructor of App\\Console\\Kernel and in any methods called in the constructor',
                    'fingerprint' => '206df1cdb86fc7fc14b049a658832473',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 2,
                            'end' => 2,
                        ],
                    ],
                ],
            ],
            $converter->getOutput()
        );
    }

    public function testItCanConvertPsalmJsonToJsonSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__.'/fixtures/output.json');

        $validatorFactory = new ValidatorFactory();

        /**
         * @var AbstractJsonValidator
         */
        $validator = $validatorFactory->build('Psalm', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        /**
         * AbstractConverter $converterImplementation.
         */
        $converterImplementation = $converterFactory->build(
            'Psalm',
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
