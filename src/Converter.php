<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use Safe\Exceptions\JsonException;

use function Safe\json_encode;

class Converter implements ConvertToSubsetInterface, OutputInterface
{
    /**
     * @var array
     */
    protected $codeClimateNodes = [];

    /**
     * @var AbstractConverter[]
     */
    private $converters;

    public function addConverter(AbstractConverter $converter): void
    {
        $this->converters[] = $converter;
    }

    public function convertToSubset(): void
    {
        if (empty($this->converters)) {
            throw new NoConvertersEnabledException();
        }

        /** @var AbstractConverter $converter */
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
}
