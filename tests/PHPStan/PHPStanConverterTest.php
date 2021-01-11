<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPStan;

use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanJsonValidator;
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

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals([], $converter->getOutput());
    }

    public function testItCanConvertPhpStanJsonToSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

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
            $converter->getOutput()
        );
    }

    public function testItCanConvertPhpStanJsonToJsonSubset(): void
    {
        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $jsonOutput = file_get_contents(__DIR__.'/fixtures/output.json');

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converter->getJsonEncodedOutput()
        );
    }
}
