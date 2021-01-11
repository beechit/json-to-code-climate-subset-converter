<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests;

use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmJsonValidator;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @internal
 */
class TestCase extends BaseTestCase
{
    /**
     * @see \BeechIt\JsonToCodeClimateSubsetConverter\Tests\ConverterTest::testItCanConvertOneConvertersJsonToSubset
     * @see \BeechIt\JsonToCodeClimateSubsetConverter\Tests\ConverterTest::testItCanConvertOneConvertersJsonToJsonSubset
     * @see \BeechIt\JsonToCodeClimateSubsetConverter\Tests\CommandLine\CommandLineTest::testItFailsConvertersWithoutJsonInput
     */
    public function multipleConvertersProvider(): array
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
}
