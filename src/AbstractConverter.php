<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
use function dump;
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
        dump('hello world');
        try {
            dump('hello world 2');
            return json_encode(
                $a = [&$a],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        } catch (JsonException $exception) {
            dump('hello world 3');
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
