<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
use function Safe\json_encode;
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
     * AbstractConverter constructor.
     *
     * @param mixed $json
     */
    public function __construct(AbstractJsonValidator $abstractJsonValidator, $json)
    {
        $this->abstractJsonValidator = $abstractJsonValidator;

        $this->json = $json;
    }

    public function getOutput(): array
    {
        return $this->codeClimateNodes;
    }

    public function getJsonEncodedOutput(): string
    {
        try {
            return json_encode(
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

    abstract protected function getToolName(): string;

    protected function createFingerprint(
        string $description,
        string $filename,
        string $line
    ): string {
        try {
            return md5(
                sprintf(
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
