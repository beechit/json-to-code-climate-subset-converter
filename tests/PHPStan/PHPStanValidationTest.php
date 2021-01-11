<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHPStan;

use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PHPStanValidationTest extends TestCase
{
    public function testItThrowsAnExceptionWhenDescriptionPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [description] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-description-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLocationPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-location-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLocationPathPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location.path] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-location-path-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLocationLinesPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location.lines] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-location-lines-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLocationLinesBeginPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location.lines.begin] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-location-lines-begin-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PHPStanJsonValidator($jsonDecodedInput);
        $converter = new PHPStanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }
}
