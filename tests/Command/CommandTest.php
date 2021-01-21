<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Command;

use function basename;
use BeechIt\JsonToCodeClimateSubsetConverter\Command\ConverterCommand;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use function file_get_contents;
use function json_decode;
use PHLAK\Config\Config;
use function sprintf;
use function strtolower;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
class CommandTest extends TestCase
{
    public function testItFailsWhenNoConverterIsAdded(): void
    {
        // Given
        $configuration = new Config(__DIR__.'/../../config/converters.php');

        $application = new Application();
        $application->add(
            new ConverterCommand(
                'convert',
                $configuration
            )
        );

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
        array $output,
        string $name
    ): void {
        // Given
        $jsonFileName = $jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/../'.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build($name, $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            $name,
            $validator,
            $jsonDecodedInput
        );

        $configuration = new Config(__DIR__.'/../../config/converters.php');

        $application = new Application();
        $application->add(
            new ConverterCommand(
                'convert',
                $configuration
            )
        );

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
        array $output,
        string $name
    ): void {
        // Given
        $jsonFileName = $jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/../'.$jsonInput);
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build($name, $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            $name,
            $validator,
            $jsonDecodedInput
        );

        $configuration = new Config(__DIR__.'/../../config/converters.php');

        $application = new Application();
        $application->add(
            new ConverterCommand(
                'convert',
                $configuration
            )
        );

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
