<?php


namespace BeechIt\JsonToCodeClimateSubsetConverter;


interface OutputInterface
{
    public function getOutput(): array;

    public function getJsonEncodedOutput(): string;
}