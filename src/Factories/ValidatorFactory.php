<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Factories;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\NoValidatorsEnabledException;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\ValidatorFactoryInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPCSFixer\PHPCSFixerJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmJsonValidator;
use function is_null;

class ValidatorFactory implements ValidatorFactoryInterface
{
    public function build(
        string $validatorName,
        $json
    ): AbstractJsonValidator {
        $validator = null;

        switch ($validatorName) {
            case 'Phan':
                $validator = new PhanJsonValidator($json);

                break;
            case 'PHP_CodeSniffer':
                $validator = new PhpCodeSnifferJsonValidator($json);

                break;
            case 'PHPLint':
                $validator = new PhpLintJsonValidator($json);

                break;
            case 'PHPStan':
                $validator = new PHPStanJsonValidator($json);

                break;
            case 'Psalm':
                $validator = new PsalmJsonValidator($json);

                break;
            case 'PHP-CS-Fixer':
                $validator = new PHPCSFixerJsonValidator($json);

                break;
        }

        if (is_null($validator)) {
            throw new NoValidatorsEnabledException('Factory was not able to built a validator');
        }

        return $validator;
    }
}
