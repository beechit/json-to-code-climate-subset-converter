<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\NoValidatorsEnabledException;
use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\UnableToGetJsonEncodedOutputException;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\ConvertToSubsetInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\OutputInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\SafeMethods;
use Safe\Exceptions\JsonException;

class Converter implements ConvertToSubsetInterface, OutputInterface
{
    /**
     * @var array
     */
    protected $codeClimateNodes = [];

    /**
     * @var AbstractConverter[]
     */
    private $converters = [];

    /**
     * @var SafeMethodsInterface
     */
    private $safeMethods;

    public function __construct(SafeMethodsInterface $safeMethods = null)
    {
        $this->safeMethods = $safeMethods ?: new SafeMethods();
    }

    public function addConverter(AbstractConverter $converter): void
    {
        $this->converters[] = $converter;
    }

    public function convertToSubset(): void
    {
        if (empty($this->converters)) {
            throw new NoValidatorsEnabledException();
        }

        foreach ($this->converters as $converter) {
            $converter->convertToSubset();

            $this->codeClimateNodes = array_merge(
                $this->codeClimateNodes,
                $converter->getOutput()
            );
        }
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
}
