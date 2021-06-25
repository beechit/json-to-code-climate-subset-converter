<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Factories;

use BeechIt\JsonToCodeClimateSubsetConverter\Command\ConverterCommand;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\CommandFactoryInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Interfaces\SafeMethodsInterface;
use BeechIt\JsonToCodeClimateSubsetConverter\Utilities\SafeMethods;
use PHLAK\Config\Config;
use Symfony\Component\Console\Command\Command;

class CommandFactory implements CommandFactoryInterface
{
    public function build(
        string $name,
        Config $configuration = null,
        SafeMethodsInterface $safeMethods = null
    ): Command {
        $configuration = $configuration ?: new Config(include __DIR__.'/../../config/converters.php');
        $safeMethods = $safeMethods ?: new SafeMethods();

        return new ConverterCommand(
            $name,
            $configuration,
            $safeMethods
        );
    }
}
