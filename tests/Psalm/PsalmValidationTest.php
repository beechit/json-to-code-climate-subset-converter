<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Psalm;

use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;

/**
 * @internal
 */
class PsalmValidationTest extends TestCase
{
    public function testItThrowsAnExceptionWhenDescriptionPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [message] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-message-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PsalmJsonValidator($jsonDecodedInput);
        $converter = new PsalmConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenFileNamePropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [file_name] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-file-name-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PsalmJsonValidator($jsonDecodedInput);
        $converter = new PsalmConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLineFromPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [line_from] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-line-from-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PsalmJsonValidator($jsonDecodedInput);
        $converter = new PsalmConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function testItThrowsAnExceptionWhenLineToPropertyIsMissing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [line_to] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/fixtures/invalid-line-to-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PsalmJsonValidator($jsonDecodedInput);
        $converter = new PsalmConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }
}
