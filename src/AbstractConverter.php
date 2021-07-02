<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateDescription;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateFingerprint;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToGetJsonEncodedOutputException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ConverterFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\ConvertToSubsetInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\OutputInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;
use function debug_backtrace;
use LogicException;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;

abstract class AbstractConverter implements OutputInterface, ConvertToSubsetInterface
{
    const DEBUG_BACKTRACE_LIMIT = 2;

    /**
     * @var AbstractJsonValidator
     */
    protected $abstractJsonValidator;

    /**
     * @var mixed
     */
    protected $json;

    /**
     * @var array
     */
    protected $codeClimateNodes = [];

    /**
     * @var SafeMethodsInterface
     */
    private $safeMethods;

    /**
     * AbstractConverter constructor.
     *
     * @param mixed $json
     */
    public function __construct(
        AbstractJsonValidator $abstractJsonValidator,
        $json,
        SafeMethodsInterface $safeMethods
    ) {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, self::DEBUG_BACKTRACE_LIMIT);
        $constructingClass = $backtrace[1]['class'];

        if (ConverterFactory::class !== $constructingClass) {
            throw new LogicException('Converter was not built via it\'s factory');
        }

        $this->abstractJsonValidator = $abstractJsonValidator;
        $this->json = $json;
        $this->safeMethods = $safeMethods;
    }

    public function getOutput(): array
    {
        return $this->codeClimateNodes;
    }

    public function getJsonEncodedOutput(): string
    {
        try {
            return $this->safeMethods->json_encode(
                $this->getOutput(),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        } catch (JsonException $exception) {
            throw new UnableToGetJsonEncodedOutputException(
                $exception->getMessage()
            );
        }
    }

    abstract public function convertToSubset(): void;

    abstract public function getToolName(): string;

    protected function createFingerprint(
        string $description,
        string $filename,
        int $line
    ): string {
        try {
            return md5(
                $this->safeMethods->sprintf(
                    '%s%s%s',
                    $description,
                    $filename,
                    $line
                )
            );
        } catch (StringsException $exception) {
            throw new UnableToCreateFingerprint(
                $exception->getMessage()
            );
        }
    }

    protected function createDescription(string $description): string
    {
        try {
            return $this->safeMethods->sprintf(
                '(%s) %s',
                $this->getToolName(),
                $description
            );
        } catch (StringsException $exception) {
            throw new UnableToCreateDescription(
                $exception->getMessage()
            );
        }
    }
}
