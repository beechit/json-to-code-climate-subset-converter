<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter;

abstract class AbstractJsonValidator
{
    /** @var mixed $json */
    protected $json;

    public function __construct($json)
    {
        $this->json = $json;
    }
}
