<?php

namespace PhpVdChecker;

class Error
{
    /**
     * @var int
     */
    private $line;

    /**
     * @var string
     */
    private $message;

    public function __construct(int $line, string $message)
    {
        $this->line = $line;
        $this->message = $message;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}