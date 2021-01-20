<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateDescription;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToCreateFingerprint;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToGetJsonEncodedOutputException;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\ConvertToSubsetInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\OutputInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
use function Safe\sprintf;

abstract class AbstractConverter implements OutputInterface, ConvertToSubsetInterface
{
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
        SafeMethodsInterface $safeMethods = null
    ) {
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
            return sprintf(
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
