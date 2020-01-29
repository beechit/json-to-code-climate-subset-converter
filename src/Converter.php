<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

class Converter implements ConvertToSubsetInterface, OutputInterface
{
    /** @var array $codeClimateNodes */
    protected $codeClimateNodes = [];

    /** @var AbstractConverter[] $converters */
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
        return json_encode(
            $this->getOutput(),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
