<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Factories;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\NoConvertersEnabledException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use function file_get_contents;
use function json_decode;

/**
 * @internal
 */
class ConverterFactoryTest extends TestCase
{
    public function testItThrowsExceptionWhenInvalidConverterNameIsGiven(): void
    {
        $this->expectException(NoConvertersEnabledException::class);
        $this->expectExceptionMessage('Factory was not able to built a converter');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/../PHPLint/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPLint', $jsonDecodedInput);

        $factory = new ConverterFactory();

        // When
        $factory->build(
            'InvalidConverterName',
            $validator,
            $jsonDecodedInput
        );
    }
}
