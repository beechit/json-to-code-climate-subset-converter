<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

use BeechIt\JsonToCodeClimateSubsetConverter\Exceptions\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\Factories\ValidatorFactory;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\JsonValidatorInterface;
use function debug_backtrace;

abstract class AbstractJsonValidator implements JsonValidatorInterface
{
    const DEBUG_BACKTRACE_LIMIT = 2;

    /**
     * @var array
     */
    protected $json;

    /**
     * AbstractJsonValidator constructor.
     *
     * @param mixed $json
     */
    public function __construct($json)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, self::DEBUG_BACKTRACE_LIMIT);
        $constructingClass = $backtrace[1]['class'];

        if (ValidatorFactory::class !== $constructingClass) {
            throw new \LogicException('Validator was not built via it\'s factory');
        }

        $this->json = $json;
    }

    /**
     * @throws InvalidJsonException
     */
    abstract public function validateJson(): void;
}
