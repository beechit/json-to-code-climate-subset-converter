<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\CommandLine;

use function basename;
use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\AbstractJsonValidator;
use BeechIt\JsonToCodeClimateSubsetConverter\ConverterCommand;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use function file_get_contents;
use function json_decode;
use function sprintf;
use function strtolower;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
class CommandLineTest extends TestCase
{
    public function testItFailsWhenNoConverterIsAdded(): void
    {
        // Given
        $application = new Application();
        $application->add(new ConverterCommand());

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        // When
        $commandTester->execute([]);

        // Then
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Please include at least 1 converter.', $output);
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItFailsConvertersWithoutJsonInput(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output
    ): void {
        // Given
        $jsonFileName = $jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/../'.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);

        /** @var AbstractJsonValidator $validator */
        $validator = new $validator($jsonDecodedInput);

        /** @var AbstractConverter $converterImplementation */
        $converterImplementation = new $converter($validator, $jsonDecodedInput);

        $application = new Application();
        $application->add(new ConverterCommand());

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        $converterImplementationOptionName = sprintf(
            '--%s',
            strtolower($converterImplementation->getToolName())
        );

        $filename = str_replace(
            'input',
            strtolower($converterImplementation->getToolName()),
            basename($jsonFileName)
        );

        // When
        $commandTester->execute(
            [
                $converterImplementationOptionName => true,
            ]
        );

        // Then
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            sprintf(
                'Unable to find %s. See error code 1.',
                $filename
            ),
            $output
        );
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItAcceptsFileFromPath(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output
    ): void {
        // Given
        $jsonFileName = $jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/../'.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);

        /** @var AbstractJsonValidator $validator */
        $validator = new $validator($jsonDecodedInput);

        /** @var AbstractConverter $converterImplementation */
        $converterImplementation = new $converter($validator, $jsonDecodedInput);

        $application = new Application();
        $application->add(new ConverterCommand());

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        $converterImplementationOptionName = sprintf(
            '--%s',
            strtolower($converterImplementation->getToolName())
        );

        $converterImplementationOptionFilePath = sprintf(
            '--%s-json-file',
            strtolower($converterImplementation->getToolName())
        );

        // When
        $commandTester->execute(
            [
                $converterImplementationOptionName => true,
                $converterImplementationOptionFilePath => $jsonFileName,
            ]
        );

        // Then
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            sprintf(
                'Converting %s via %s.',
                $converterImplementation->getToolName(),
                $jsonFileName
            ),
            $output
        );
    }
}
