<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Factories;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\NoConvertersEnabledException;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\ConverterFactoryInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\SafeMethods;
use function is_null;

class ConverterFactory implements ConverterFactoryInterface
{
    public function build(
        string $converterName,
        AbstractJsonValidator $validator,
        $json,
        SafeMethodsInterface $safeMethods = null
    ): AbstractConverter {
        $converter = null;

        $safeMethods = $safeMethods ?: new SafeMethods();

        switch ($converterName) {
            case 'Phan':
                $converter = new PhanConvertToSubset(
                    $validator,
                    $json,
                    $safeMethods
                );

                break;
            case 'PHP_CodeSniffer':
                $converter = new PhpCodeSnifferConvertToSubset(
                    $validator,
                    $json,
                    $safeMethods
                );

                break;
            case 'PHPLint':
                $converter = new PhpLintConvertToSubset(
                    $validator,
                    $json,
                    $safeMethods
                );

                break;
            case 'PHPStan':
                $converter = new PHPStanConvertToSubset(
                    $validator,
                    $json,
                    $safeMethods
                );

                break;
            case 'Psalm':
                $converter = new PsalmConvertToSubset(
                    $validator,
                    $json,
                    $safeMethods
                );

                break;
        }

        if (is_null($converter)) {
            throw new NoConvertersEnabledException('Factory was not able to built a converter');
        }

        return $converter;
    }
}
