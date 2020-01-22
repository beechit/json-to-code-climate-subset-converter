<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\PHP_CodeSniffer;

use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferConvertToSubset;

class PhpCodeSnifferValidationTest extends TestCase
{
    public function test_it_throws_an_exception_when_files_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-files-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpCodeSnifferJsonValidator($jsonDecodedInput);
        $converter = new PhpCodeSnifferConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_files_messages_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files.messages] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-files-messages-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpCodeSnifferJsonValidator($jsonDecodedInput);
        $converter = new PhpCodeSnifferConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_files_messages_message_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files.messages.message] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-files-messages-message-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpCodeSnifferJsonValidator($jsonDecodedInput);
        $converter = new PhpCodeSnifferConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }

    public function test_it_throws_an_exception_when_files_messages_line_property_is_missing()
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectErrorMessage('The [files.messages.line] is a required property');

        // Given
        $jsonInput = file_get_contents(__DIR__ . '/fixtures/invalid-files-messages-line-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        // When
        $validator = new PhpCodeSnifferJsonValidator($jsonDecodedInput);
        $converter = new PhpCodeSnifferConvertToSubset($validator, $jsonDecodedInput);
        $converter->convertToSubset();
    }
}
