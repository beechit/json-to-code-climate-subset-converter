<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Phan\PhanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer\PhpCodeSnifferJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPLint\PhpLintJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPMD\PhpMDConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPMD\PhpMDJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\PHPStan\PHPStanJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmConvertToSubset;
use BeechIt\JsonToCodeClimateSubsetConverter\Psalm\PsalmJsonValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConverterCommand extends Command
{
    /**
     * @var string $defaultName
     */
    protected static $defaultName = 'convert';

    /**
     * @var array $supportedConverters
     */
    private static $supportedConverters = [
        'Phan' => [
            'validator' => PhanJsonValidator::class,
            'converter' => PhanConvertToSubset::class,
        ],
        'PHP_CodeSniffer' => [
            'validator' => PhpCodeSnifferJsonValidator::class,
            'converter' => PhpCodeSnifferConvertToSubset::class,
        ],
        'PHPLint' => [
            'validator' => PhpLintJsonValidator::class,
            'converter' => PhpLintConvertToSubset::class,
        ],
        'PHPStan' => [
            'validator' => PHPStanJsonValidator::class,
            'converter' => PHPStanConvertToSubset::class,
        ],
        'Psalm' => [
            'validator' => PsalmJsonValidator::class,
            'converter' => PsalmConvertToSubset::class,
        ],
    ];

    protected function configure(): void
    {
        foreach (static::$supportedConverters as $converterName => $converter) {
            $this->option($converterName);
        }

        $this->addOption(
            'output',
            null,
            InputOption::VALUE_OPTIONAL,
            'Where to output JSON',
            'code-climate.json'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $converter = new Converter();

        foreach (static::$supportedConverters as $converterName => $supportedConverter) {
            if (false !== $input->getOption(strtolower($converterName))) {
                $filename = $input->getOption(
                    \Safe\sprintf(
                        '%s-json-file',
                        strtolower($converterName)
                    )
                );

                $output->writeln(
                    \Safe\sprintf(
                        '<comment>Converting %s via %s</comment>',
                        $converterName,
                        $filename
                    )
                );

                $jsonInput = \Safe\file_get_contents($filename);
                $jsonDecodedInput = \Safe\json_decode($jsonInput);

                /**
                 * @var AbstractJsonValidator $validator
                 */
                $validator = new $supportedConverter['validator']($jsonDecodedInput);

                /**
                 * AbstractConverter $converterImplementation
                 */
                $converterImplementation = new $supportedConverter['converter']($validator, $jsonDecodedInput);

                $converter->addConverter($converterImplementation);
            }
        }

        try {
            $converter->convertToSubset();

            $outputFilename = $input->getOption('output');

            $output->writeln(
                \Safe\sprintf(
                    '<info>Writing output to %s</info>',
                    $outputFilename
                )
            );

            \Safe\file_put_contents(
                $outputFilename,
                $converter->getJsonEncodedOutput()
            );
        } catch (NoConvertersEnabledException $exception) {
            $output->writeln('<error>Please include at least 1 converter.</error>');
        }

        return 1;
    }

    private function option(string $converter): void
    {
        $this->addOption(
            strtolower($converter),
            null,
            InputOption::VALUE_OPTIONAL,
            \Safe\sprintf(
                'Include %s converter',
                $converter
            ),
            false
        );

        $this->addOption(
            \Safe\sprintf(
                '%s-json-file',
                strtolower($converter)
            ),
            null,
            InputOption::VALUE_OPTIONAL,
            'Location to JSON file',
            \Safe\sprintf(
                '%s.json',
                strtolower($converter)
            )
        );
    }
}
