<?php

declare(strict_types=1);

namespace BeechIt\JsonToCodeClimateSubsetConverter;

interface OutputInterface
{
    public function getOutput(): array;

    public function getJsonEncodedOutput(): string;
}
