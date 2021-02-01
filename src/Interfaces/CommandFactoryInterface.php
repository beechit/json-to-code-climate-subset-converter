<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

use PHLAK\Config\Config;
use Symfony\Component\Console\Command\Command;

interface CommandFactoryInterface
{
    public function build(
        string $name,
        Config $configuration,
        SafeMethodsInterface $safeMethods = null
    ): Command;
}
