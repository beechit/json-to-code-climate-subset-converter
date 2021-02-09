<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Tests\Command;

use function basename;
use BeechIt\JsonToCodeClimateSubsetConverter\Command\ConverterCommand;
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
use Safe\Exceptions\FilesystemException;
use Safe\Exceptions\JsonException;
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

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItFailsWhenItCanNotGetFileContents(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void {
        // Given
        $safeMethods = $this->getMockBuilder(SafeMethods::class)
            ->onlyMethods(
                [
                    'file_get_contents',
                ]
            )
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $safeMethods->method('file_get_contents')
            ->willThrowException(new FilesystemException());

        $jsonFileName = __DIR__.'/..'.$jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/..'.$jsonInput);
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
        $command = $commandFactory->build(
            'convert',
            null,
            $safeMethods
        );

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
                'Unable to get file contents from %s. See error code %d.',
                $jsonFileName,
                ConverterCommand::EXIT_UNABLE_TO_GET_FILE_CONTENTS
            ),
            $output
        );
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItFailsWhenItCanNotDecodeJsonFileContents(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void {
        // Given
        $safeMethods = $this->getMockBuilder(SafeMethods::class)
            ->onlyMethods(
                [
                    'json_decode',
                ]
            )
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $safeMethods->method('json_decode')
            ->willThrowException(new JsonException());

        $jsonFileName = __DIR__.'/..'.$jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/..'.$jsonInput);
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
        $command = $commandFactory->build(
            'convert',
            null,
            $safeMethods
        );

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
                'Unable to decode %s. See error code %d.',
                $jsonFileName,
                ConverterCommand::EXIT_UNABLE_TO_DECODE_FILE
            ),
            $output
        );
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItCanConvertJsonToCodeClimateSubset(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void {
        // Given
        $jsonFileName = __DIR__.'/..'.$jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/..'.$jsonInput);
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
            'Writing output to code-climate.json.',
            $output
        );
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItFailsWhenItCanNotWriteToOutputFile(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void {
        // Given
        $safeMethods = $this->getMockBuilder(SafeMethods::class)
            ->onlyMethods(
                [
                    'file_put_contents',
                ]
            )
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $safeMethods->method('file_put_contents')
            ->willThrowException(new FilesystemException());

        $jsonFileName = __DIR__.'/..'.$jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/..'.$jsonInput);
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
        $command = $commandFactory->build(
            'convert',
            null,
            $safeMethods
        );

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
                'Unable to write to output file. See error code %d.',
                ConverterCommand::EXIT_UNABLE_TO_WRITE_FILE
            ),
            $output
        );
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItFailsWhenItCanNotGetEncodedOutput(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void {
        // Given
        $safeMethods = $this->getMockBuilder(SafeMethods::class)
            ->onlyMethods(
                [
                    'json_encode',
                ]
            )
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $safeMethods->method('json_encode')
            ->willThrowException(new JsonException());

        $jsonFileName = __DIR__.'/..'.$jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/..'.$jsonInput);
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
        $command = $commandFactory->build(
            'convert',
            null,
            $safeMethods
        );

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
                'Unable to get JSON encoded output. See error code %d.',
                ConverterCommand::EXIT_UNABLE_TO_GET_ENCODED_OUTPUT
            ),
            $output
        );
    }

    public function testItFailsWhenItCanNotInformUserAboutSuccessfullyWritingToOutput(): void
    {
        $this->expectException(UnableToWriteOutputLine::class);

        // Given
        $configuration = $this->createMock(Config::class);

        $configuration->method('get')
            ->with('converters')
            ->willReturn(
                [
                    'PHPLint',
                ]
            );

        $safeMethods = $this->getMockBuilder(SafeMethods::class)
            ->onlyMethods(
                [
                    'sprintf',
                ]
            )
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $safeMethods->method('sprintf')
            ->will(
                $this->onConsecutiveCalls(
                    '',
                    'phplint-json-file',
                    'phplint.json',
                    'phplint-json-file',
                    'phplint-json-file.json',
                    $this->throwException(new StringsException())
                )
            );

        $application = new Application();

        $commandFactory = new CommandFactory();
        $command = $commandFactory->build(
            'convert',
            $configuration,
            $safeMethods
        );

        $application->add($command);

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);

        // When
        $commandTester->execute([
            '--phplint' => true,
            '--phplint-json-file' => __DIR__.'/../fixtures/input.json',
        ]);
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItCanConvertJsonToCodeClimateSubsetAndOptionallyFailWithNonZeroExitCode(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void {
        // Given
        $jsonFileName = __DIR__.'/..'.$jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/..'.$jsonInput);
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
                '--fail-on-convert' => true,
            ]
        );

        // Then
        $this->assertEquals(
            ConverterCommand::EXIT_FAIL_ON_CONVERT,
            $commandTester->getStatusCode()
        );
    }

    public function testItExitsWithZeroExitCodeWhenNoErrorsWereConvertedWhenFailOnConvertIsEnabled(): void
    {
        // Given
        $jsonFileName = __DIR__.'/../fixtures/empty-input.json';
        $jsonInput = file_get_contents(__DIR__.'/../fixtures/empty-input.json');
        $jsonDecodedInput = json_decode($jsonInput);

        $validatorFactory = new ValidatorFactory();

        $validator = $validatorFactory->build('PHPStan', $jsonDecodedInput);

        $converterFactory = new ConverterFactory();

        $converterImplementation = $converterFactory->build(
            'PHPStan',
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
                '--fail-on-convert' => true,
            ]
        );

        // Then
        $this->assertEquals(
            ConverterCommand::EXIT_NO_ERRORS,
            $commandTester->getStatusCode()
        );
    }

    /**
     * @dataProvider multipleConvertersProvider
     */
    public function testItExitsWithZeroExitCodeWhenErrorsWereConvertedWhenFailOnConvertIsDisabledByDefault(
        string $jsonInput,
        string $jsonOutput,
        string $validator,
        string $converter,
        array $output,
        string $name
    ): void
    {
        // Given
        $jsonFileName = __DIR__.'/..'.$jsonInput;
        $jsonInput = file_get_contents(__DIR__.'/..'.$jsonInput);
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
                $converterImplementationOptionFilePath => $jsonFileName
            ]
        );

        // Then
        $this->assertEquals(
            ConverterCommand::EXIT_NO_ERRORS,
            $commandTester->getStatusCode()
        );
    }
}
