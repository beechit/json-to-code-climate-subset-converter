<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPLint;

use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\UnableToGetJsonEncodedOutputException;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\JsonEncode;
use function file_get_contents;
use function json_decode;
use phpmock\phpunit\PHPMock;
use Safe\Exceptions\JsonException;

/**
 * @internal
 */
class PhpLintConverterTest extends TestCase
{
    use PHPMock;

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

        // When
        $validator = new PhpLintJsonValidator($jsonDecodedInput);
        $converter = new PhpLintConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converter->getJsonEncodedOutput()
        );
    }

    public function testItCanThrowAnExceptionWhenConvertingPhpLintJsonToJsonSubsetFails(): void
    {
        $this->expectException(UnableToGetJsonEncodedOutputException::class);

        $converter = $this->createMock(PhpLintConvertToSubset::class);

        $converter->method('jsonEncode')
            ->willThrowException(new JsonException());

        $converter->getJsonEncodedOutput();
    }
}
