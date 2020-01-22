<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Phan;

use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanConvertToSubset;

class PhanValidationTest extends TestCase
{
    public function test_it_throws_an_exception_when_description_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [description] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-description-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhanJsonValidator($jsonDecodedInput);
        $converter = new PhanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_location_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-location-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhanJsonValidator($jsonDecodedInput);
        $converter = new PhanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_location_path_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location.path] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-location-path-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhanJsonValidator($jsonDecodedInput);
        $converter = new PhanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_location_lines_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location.lines] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-location-lines-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhanJsonValidator($jsonDecodedInput);
        $converter = new PhanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_location_lines_begin_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [location.lines.begin] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-location-lines-begin-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhanJsonValidator($jsonDecodedInput);
        $converter = new PhanConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }
}
