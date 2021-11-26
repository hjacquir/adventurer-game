<?php

declare(strict_types=1);

namespace App\Domain;

class LatitudeLine
{
    private string $line;
    private int $latitude;

    /**
     * @param string $line
     * @param int $latitude
     */
    public function __construct(string $line, int $latitude)
    {
        $this->line = $line;
        $this->latitude = $latitude;
    }

    public function getLine(): string
    {
        return $this->line;
    }

    public function getLatitude(): int
    {
        return $this->latitude;
    }

    /**
     * @param string $line
     */
    public function setLine(string $line): void
    {
        $this->line = $line;
    }

    /**
     * @param int $latitude
     */
    public function setLatitude(int $latitude): void
    {
        $this->latitude = $latitude;
    }
}
