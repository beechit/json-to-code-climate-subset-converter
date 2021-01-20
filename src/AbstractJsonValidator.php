<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\JsonValidatorInterface;

abstract class AbstractJsonValidator implements JsonValidatorInterface
{
    /**
     * @var array
     */
    protected $json = [];

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
