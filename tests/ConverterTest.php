<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests;

use BeechIt\JsonToCodeClimateSubsetConverter\Converter;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToGetJsonEncodedOutputException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\SafeMethods;
use function file_get_contents;
use function json_decode;
use Safe\Exceptions\JsonException;

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
        array $output,
        string $name
    ): void {
        // Given
        $jsonInput = file_get_contents(__DIR__.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build($name, $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            $name,
            $validator,
            $jsonDecodedInput
        );

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
        array $output,
        string $name
    ): void {
        // Given
        $jsonInput = file_get_contents(__DIR__.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build($name, $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            $name,
            $validator,
            $jsonDecodedInput
        );

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

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItThrowsExceptionWhenItCanNotGetJsonEncodedOutput(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void {
        $this->expectException(UnableToGetJsonEncodedOutputException::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('json_encode')
            ->willThrowException(new JsonException());

        $jsonInput = file_get_contents(__DIR__.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build($name, $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            $name,
            $validator,
            $jsonDecodedInput
        );

        // When
        $converter = new Converter($safeMethods);
        $converter->addConverter($converterImplementation);
        $converter->getJsonEncodedOutput();
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

            $validatorFactory = new ValidatorFactory();

            $validator = $validatorFactory->build($converterDetails['name'], $jsonDecodedInput);

            $converterFactory = new ConverterFactory();

            $converterImplementation = $converterFactory->build(
                $converterDetails['name'],
                $validator,
                $jsonDecodedInput
            );

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

            $validatorFactory = new ValidatorFactory();

            $validator = $validatorFactory->build($converterDetails['name'], $jsonDecodedInput);

            $converterFactory = new ConverterFactory();

            $converterImplementation = $converterFactory->build(
                $converterDetails['name'],
                $validator,
                $jsonDecodedInput
            );

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
