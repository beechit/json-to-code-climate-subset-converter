<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

abstract class AbstractJsonValidator
{
    /**
     * @var mixed $json
     */
    protected $json;

    /**
     * AbstractJsonValidator constructor.
     * @param mixed $json
     */
    public function __construct($json)
    {
        $this->json = $json;
    }
}
