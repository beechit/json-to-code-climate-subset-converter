<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Psalm;

use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmConvertToSubset;

class PsalmConverterTest extends TestCase
{
    public function test_it_can_convert_psalm_json_to_subset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/input.json');
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
                ]
            ],
            $converter->getOutput()
        );
    }

    public function test_it_can_convert_psalm_json_to_json_subset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__ . '/fixtures/output.json');

        // When
        $validator = new PsalmJsonValidator($jsonDecodedInput);
        $converter = new PsalmConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converter->getJsonEncodedOutput()
        );
    }
}
