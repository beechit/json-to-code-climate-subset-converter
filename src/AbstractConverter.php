<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

abstract class AbstractConverter implements OutputInterface
{
    /** @var AbstractJsonValidator */
    protected $abstractJsonValidator;

    /** @var mixed $json */
    protected $json;

    /** @var array $codeClimateNodes */
    protected $codeClimateNodes;

    public function __construct(AbstractJsonValidator $abstractJsonValidator, $json)
    {
        $this->abstractJsonValidator = $abstractJsonValidator;

        $this->json = $json;
    }

    abstract protected function getToolName(): string;

    protected function createFingerprint($description, $filename, $line): string
    {
        return md5(
            sprintf(
                '%s%s%s',
                $description,
                $filename,
                $line
            )
        );
    }

    protected function createDescription(string $description): string
    {
        return sprintf(
            '(%s) %s',
            $this->getToolName(),
            $description
        );
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