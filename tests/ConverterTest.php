<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests;

use BeechIt\JsonToCodeClimateSubsetConverter\Converter;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPMD\PhpMDJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPMD\PhpMDConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferConvertToSubset;

class ConverterTest extends TestCase
{
    /**
     * @see \BeechIt\JsonToCodeClimateSubsetConverter\Tests\ConverterTest::test_it_can_convert_one_converters_json_to_subset
     */
    public function multipleConvertersProvider()
    {
        return [
            'Phan' => [
                'jsonInput' => '/Phan/fixtures/input.json',
                'jsonOutput' => '/Phan/fixtures/output.json',
                'validator' => PhanJsonValidator::class,
                'converter' => PhanConvertToSubset::class,
                'output' => [
                    'description' => '(Phan) UndefError PhanUndeclaredClassConstant Reference to constant class from undeclared class \PhpParser\Node\Stmt\ClassMethod',
                    'fingerprint' => 'e8547906ee21b4f8e8804de980a9d239',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 32,
                            'end' => 34,
                        ],
                    ],
                ],
            ],
            'PHP_CodeSniffer' => [
                'jsonInput' => '/PHP_CodeSniffer/fixtures/input.json',
                'jsonOutput' => '/PHP_CodeSniffer/fixtures/output.json',
                'validator' => PhpCodeSnifferJsonValidator::class,
                'converter' => PhpCodeSnifferConvertToSubset::class,
                'output' => [
                    'description' => '(PHP_CodeSniffer) Missing file doc comment',
                    'fingerprint' => 'fa33b2f8044e0f23de6b53f15d4d7bc9',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 2,
                        ],
                    ],
                ],
            ],
            'PHPLint' => [
                'jsonInput' => '/PHPLint/fixtures/input.json',
                'jsonOutput' => '/PHPLint/fixtures/output.json',
                'validator' => PhpLintJsonValidator::class,
                'converter' => PhpLintConvertToSubset::class,
                'output' => [
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
            'PHPMD' => [
                'jsonInput' => '/PHPMD/fixtures/input.json',
                'jsonOutput' => '/PHPMD/fixtures/output.json',
                'validator' => PhpMDJsonValidator::class,
                'converter' => PhpMDConvertToSubset::class,
                'output' => [
                    'description' => '(PHPMD) Avoid unused parameters such as \'$schedule\'.',
                    'fingerprint' => '70f480e1580e09ea094ac164e72a53bc',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 2,
                            'end' => 2,
                        ],
                    ],
                ],
            ],
            'PHPStan' => [
                'jsonInput' => '/PHPStan/fixtures/input.json',
                'jsonOutput' => '/PHPStan/fixtures/output.json',
                'validator' => PHPStanJsonValidator::class,
                'converter' => PHPStanConvertToSubset::class,
                'output' => [
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
            'Psalm' => [
                'jsonInput' => '/Psalm/fixtures/input.json',
                'jsonOutput' => '/Psalm/fixtures/output.json',
                'validator' => PsalmJsonValidator::class,
                'converter' => PsalmConvertToSubset::class,
                'output' => [
                    'description' => '(Psalm) Property Illuminate\\Foundation\\Console\\Kernel::$artisan is not defined in constructor of App\\Console\\Kernel and in any methods called in the constructor',
                    'fingerprint' => '206df1cdb86fc7fc14b049a658832473',
                    'location' => [
                        'path' => 'app/Class.php',
                        'lines' => [
                            'begin' => 2,
                            'end' => 2,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $jsonInput
     * @param string $jsonOutput
     * @param string $validator
     * @param string $converter
     * @param array $output
     *
     * @dataProvider multipleConvertersProvider
     */
    public function test_it_can_convert_one_converters_json_to_subset(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output
    )
    {
        // Given
        $jsonInput = file_get_contents(__DIR__ . $jsonInput);
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
     * @param string $jsonInput
     * @param string $jsonOutput
     * @param string $validator
     * @param string $converter
     * @param array $output
     *
     * @dataProvider multipleConvertersProvider
     */
    public function test_it_can_convert_one_converters_json_to_json_subset(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output
    )
    {
        // Given
        $jsonInput = file_get_contents(__DIR__ . $jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);
        $validator = new $validator($jsonDecodedInput);
        $converterImplementation = new $converter($validator, $jsonDecodedInput);

        $jsonOutput = file_get_contents(__DIR__ . $jsonOutput);

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

    public function test_it_can_convert_multiple_converters_json_to_subset()
    {
        // Given
        $output = [];
        $converter = new Converter();
        $converters = $this->multipleConvertersProvider();

        /** @var array $converterDetails */
        foreach ($converters as $converterDetails) {
            $jsonInput = file_get_contents(__DIR__ . $converterDetails['jsonInput']);
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

    public function test_it_can_convert_multiple_converters_json_to_json_subset()
    {
        // Given
        $converter = new Converter();
        $converters = $this->multipleConvertersProvider();

        $jsonOutput = file_get_contents(__DIR__ . '/fixtures/output.json');

        /** @var array $converterDetails */
        foreach ($converters as $converterDetails) {
            $jsonInput = file_get_contents(__DIR__ . $converterDetails['jsonInput']);
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
