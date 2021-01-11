<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests;

use BeechIt\JsonToCodeClimateSubsetConverter\Converter;

/**
 * @internal
 */
class ConverterTest extends TestCase
{
    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItCanConvertOneConvertersJsonToSubset(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output
    ): void {
        // Given
        $jsonInput = file_get_contents(__DIR__.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);
        $validator = new $validator($jsonDecodedInput);
        $converterImplementation = new $converter($validator, $jsonDecodedInput);

        // When
        $converter = new Converter();
        $converter->addConverter($converterImplementation);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            [
                $output,
            ],
            $converter->getOutput()
        );
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItCanConvertOneConvertersJsonToJsonSubset(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output
    ): void {
        // Given
        $jsonInput = file_get_contents(__DIR__.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);
        $validator = new $validator($jsonDecodedInput);
        $converterImplementation = new $converter($validator, $jsonDecodedInput);

        $jsonOutput = file_get_contents(__DIR__.$jsonOutput);

        // When
        $converter = new Converter();
        $converter->addConverter($converterImplementation);
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converter->getJsonEncodedOutput()
        );
    }

    public function testItCanConvertMultipleConvertersJsonToSubset(): void
    {
        // Given
        $output = [];
        $converter = new Converter();
        $converters = $this->multipleConvertersProvider();

        foreach ($converters as $converterDetails) {
            $jsonInput = file_get_contents(__DIR__.$converterDetails['jsonInput']);
            $jsonDecodedInput = json_decode($jsonInput);
            $validator = new $converterDetails['validator']($jsonDecodedInput);
            $converterImplementation = new $converterDetails['converter']($validator, $jsonDecodedInput);

            $converter->addConverter($converterImplementation);

            $output[] = $converterDetails['output'];
        }

        // When
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            $output,
            $converter->getOutput()
        );
    }

    public function testItCanConvertMultipleConvertersJsonToJsonSubset(): void
    {
        // Given
        $converter = new Converter();
        $converters = $this->multipleConvertersProvider();

        $jsonOutput = file_get_contents(__DIR__.'/fixtures/output.json');

        foreach ($converters as $converterDetails) {
            $jsonInput = file_get_contents(__DIR__.$converterDetails['jsonInput']);
            $jsonDecodedInput = json_decode($jsonInput);
            $validator = new $converterDetails['validator']($jsonDecodedInput);
            $converterImplementation = new $converterDetails['converter']($validator, $jsonDecodedInput);

            $converter->addConverter($converterImplementation);
        }

        // When
        $converter->convertToSubset();

        // Then
        $this->assertEquals(
            $jsonOutput,
            $converter->getJsonEncodedOutput()
        );
    }
}
