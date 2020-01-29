<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

abstract class AbstractConverter implements OutputInterface
{
    /**
     * @var AbstractJsonValidator
     */
    protected $abstractJsonValidator;

    /**
     * @var mixed $json
     */
    protected $json;

    /**
     * @var array $codeClimateNodes
     */
    protected $codeClimateNodes;

    /**
     * AbstractConverter constructor.
     * @param AbstractJsonValidator $abstractJsonValidator
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
        return \Safe\json_encode(
            $this->getOutput(),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    abstract protected function getToolName(): string;

    protected function createFingerprint(
        string $description,
        string $filename,
        string $line
    ): string
    {
        return md5(
            \Safe\sprintf(
                '%s%s%s',
                $description,
                $filename,
                $line
            )
        );
    }

    protected function createDescription(string $description): string
    {
        return \Safe\sprintf(
            '(%s) %s',
            $this->getToolName(),
            $description
        );
    }
}
