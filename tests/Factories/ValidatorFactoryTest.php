<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Factories;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\NoValidatorsEnabledException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use function file_get_contents;
use function json_decode;

/**
 * @internal
 */
class ValidatorFactoryTest extends TestCase
{
    public function testItThrowsExceptionWhenInvalidValidatorNameIsGiven(): void
    {
        $this->expectException(NoValidatorsEnabledException::class);
        $this->expectExceptionMessage('Factory was not able to built a validator');

        // Given
        $jsonInput = file_get_contents(__DIR__.'/../PHPLint/fixtures/input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        // When
        $validatorFactory->build(
            'InvalidValidatorName',
            $jsonDecodedInput
        );
    }
}
