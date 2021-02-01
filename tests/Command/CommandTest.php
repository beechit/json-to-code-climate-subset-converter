<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Command;

use function basename;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToAddOption;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateFilenameException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToWriteOutputLine;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\CommandFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Tests\TestCase;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\SafeMethods;
use function file_get_contents;
use function json_decode;
use PHLAK\Config\Config;
use Safe\Exceptions\StringsException;
use function sprintf;
use function strtolower;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
class CommandTest extends TestCase
{
    public function testItFailsWhenItCanNotConfigureConvertersNameOption(): void
    {
        $this->expectException(UnableToAddOption::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('sprintf')
            ->willThrowException(new StringsException());

        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build('convert', null, $safeMethods);

        $application->add($command);

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        // When
        $commandTester->execute([]);
    }

    public function testItFailsWhenItCanNotConfigureConvertersFileOption(): void
    {
        $this->expectException(UnableToAddOption::class);

        // Given
        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('sprintf')
            ->will(
                $this->onConsecutiveCalls(
                    '',
                    $this->throwException(new StringsException())
                )
            );

        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build('convert', null, $safeMethods);

        $application->add($command);

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        // When
        $commandTester->execute([]);
    }

    public function testItFailsWhenItCanNotGetConvertersFileOption(): void
    {
        $this->expectException(UnableToCreateFilenameException::class);

        // Given
        $configuration = $this->createMock(Config::class);

        $configuration->method('get')
            ->with('converters')
            ->willReturn(
                [
                    'Converter-name',
                ]
            );

        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('sprintf')
            ->will(
                $this->onConsecutiveCalls(
                    '',
                    'converter-name-json-file',
                    'converter-name-json-file.json',
                    $this->throwException(new StringsException())
                )
            );

        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build('convert', $configuration, $safeMethods);

        $application->add($command);

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        // When
        $commandTester->execute([
            '--converter-name' => true,
        ]);
    }

    public function testItFailsWhenItCanNotWriteConvertingDetailsToOutputLine(): void
    {
        $this->expectException(UnableToWriteOutputLine::class);

        // Given
        $configuration = $this->createMock(Config::class);

        $configuration->method('get')
            ->with('converters')
            ->willReturn(
                [
                    'Converter-name',
                ]
            );

        $safeMethods = $this->createMock(SafeMethods::class);

        $safeMethods->method('sprintf')
            ->will(
                $this->onConsecutiveCalls(
                    '',
                    'converter-name-json-file',
                    'converter-name-json-file.json',
                    'converter-name',
                    $this->throwException(new StringsException())
                )
            );

        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build('convert', $configuration, $safeMethods);

        $application->add($command);

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        // When
        $commandTester->execute([
            '--converter-name' => true,
        ]);
    }

    public function testItFailsWhenNoConverterIsAdded(): void
    {
        // Given
        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build('convert');

        $application->add($command);

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

        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build('convert');

        $application->add($command);

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

        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build('convert');

        $application->add($command);

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
