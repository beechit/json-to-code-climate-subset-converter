<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

abstract class AbstractJsonValidator implements JsonValidatorInterface
{
    /**
     * @var mixed
     */
    protected $json;

    /**
     * AbstractJsonValidator constructor.
     *
     * @param mixed $json
     */
    public function __construct($json)
    {
        $this->json = $json;
    }

    abstract public function validateJson(): void;
}
