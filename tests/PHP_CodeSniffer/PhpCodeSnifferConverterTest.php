<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHP_CodeSniffer;

use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferConvertToSubset;

class PhpCodeSnifferConverterTest extends TestCase
{
    public function test_it_can_convert_php_code_sniffer_json_to_subset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpCodeSnifferJsonValidator($jsonDecodedInput);
        $converter = new PhpCodeSnifferConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            [
                [
                    'description' => '(PHP_CodeSniffer) Missing file doc comment',
                    'fingerprint' => 'fa33b2f8044e0f23de6b53f15d4d7bc9',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 2,
                        ],
                    ],
                ]
            ],
            $converter->getOutput()
        );
    }

    public function test_it_can_convert_php_code_sniffer_json_to_json_subset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__ . '/fixtures/output.json');

        // When
        $validator = new PhpCodeSnifferJsonValidator($jsonDecodedInput);
        $converter = new PhpCodeSnifferConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converter->getJsonEncodedOutput()
        );
    }
}
