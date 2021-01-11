<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

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
use function file_exists;
use Safe\Exceptions\FilesystemException;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\json_decode;
use function Safe\sprintf;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConverterCommand extends Command
{
    const EXIT_NO_ERRORS = 0;
    const EXIT_NO_FILE_FOUND = 1;
    const EXIT_UNABLE_TO_DECODE_FILE = 2;
    const EXIT_NO_CONVERTER_INCLUDED = 3;
    const EXIT_UNABLE_TO_WRITE_FILE = 4;
    const EXIT_UNABLE_TO_GET_ENCODED_OUTPUT = 5;

    /**
     * @var string
     */
    protected static $defaultName = 'convert';

    /**
     * @var array
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
            try {
                $this->option($converterName);
            } catch (UnableToAddOption $exception) {
                exit();
            }
        }

        $this->addOption(
            'output',
            null,
            InputOption::VALUE_OPTIONAL,
            'Where to output JSON',
            'code-climate.json'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $converter = new Converter();
        $exitCode = self::EXIT_NO_ERRORS;

        /**
         * @var string $converterName
         * @var string $supportedConverter
         */
        foreach (static::$supportedConverters as $converterName => $supportedConverter) {
            if (false !== $input->getOption(strtolower($converterName))) {
                try {
                    /** @var string $filename */
                    $filename = $input->getOption(
                        sprintf(
                            '%s-json-file',
                            strtolower($converterName)
                        )
                    );
                } catch (StringsException $exception) {
                    throw new UnableToCreateFilenameException(
                        $exception->getMessage()
                    );
                }

                try {
                    $output->writeln(
                        sprintf(
                            '<comment>Converting %s via %s.</comment>',
                            $converterName,
                            $filename
                        )
                    );
                } catch (StringsException $exception) {
                    throw new UnableToWriteOutputLine(
                        $exception->getMessage()
                    );
                }

                if (!file_exists($filename)) {
                    $output->writeln(
                        sprintf(
                            '<error>Unable to find %s. See error code %d.</error>',
                            $filename,
                            self::EXIT_NO_FILE_FOUND
                        )
                    );

                    return self::EXIT_NO_FILE_FOUND;
                }

                try {
                    $jsonInput = file_get_contents($filename);

                    $jsonDecodedInput = json_decode($jsonInput);

                    /**
                     * @var AbstractJsonValidator
                     */
                    $validator = new $supportedConverter['validator']($jsonDecodedInput);

                    /**
                     * AbstractConverter $converterImplementation.
                     */
                    $converterImplementation = new $supportedConverter['converter']($validator, $jsonDecodedInput);

                    $converter->addConverter($converterImplementation);
                } catch (JsonException $exception) {
                    $output->writeln(
                        sprintf(
                            '<error>Unable to decode %s. See error code %d.</error>',
                            $filename,
                            self::EXIT_UNABLE_TO_DECODE_FILE
                        )
                    );

                    return self::EXIT_UNABLE_TO_DECODE_FILE;
                }
            }
        }

        try {
            $converter->convertToSubset();

            /** @var string $outputFilename */
            $outputFilename = $input->getOption('output');

            $output->writeln(
                sprintf(
                    '<info>Writing output to %s.</info>',
                    $outputFilename
                )
            );

            file_put_contents(
                $outputFilename,
                $converter->getJsonEncodedOutput()
            );
        } catch (NoConvertersEnabledException $exception) {
            $output->writeln(
                sprintf(
                    '<error>Please include at least 1 converter. See error code %d.</error>',
                    self::EXIT_NO_CONVERTER_INCLUDED
                )
            );

            return self::EXIT_NO_CONVERTER_INCLUDED;
        } catch (FilesystemException $exception) {
            $output->writeln(
                sprintf(
                    '<error>Unable to write to output file. See error code %d.</error>',
                    self::EXIT_UNABLE_TO_WRITE_FILE
                )
            );

            return self::EXIT_UNABLE_TO_WRITE_FILE;
        } catch (StringsException $exception) {
            throw new UnableToWriteOutputLine(
                $exception->getMessage()
            );
        } catch (UnableToGetJsonEncodedOutputException $exception) {
            $output->writeln(
                sprintf(
                    '<error>Unable to get JSON encoded output. See error code %d.</error>',
                    self::EXIT_UNABLE_TO_GET_ENCODED_OUTPUT
                )
            );

            return self::EXIT_UNABLE_TO_WRITE_FILE;
        }

        return $exitCode;
    }

    private function option(string $converter): void
    {
        try {
            $this->addOption(
                strtolower($converter),
                null,
                InputOption::VALUE_OPTIONAL,
                sprintf(
                    'Include %s converter',
                    $converter
                ),
                false
            );
        } catch (StringsException $exception) {
            throw new UnableToAddOption(
                $exception->getMessage()
            );
        }

        try {
            $this->addOption(
                sprintf(
                    '%s-json-file',
                    strtolower($converter)
                ),
                null,
                InputOption::VALUE_OPTIONAL,
                'Location to JSON file',
                sprintf(
                    '%s.json',
                    strtolower($converter)
                )
            );
        } catch (StringsException $exception) {
            throw new UnableToAddOption(
                $exception->getMessage()
            );
        }
    }
}
