<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter\Interfaces;

interface OutputInterface
{
    public function getOutput(): array;

    public function getJsonEncodedOutput(): string;
}
