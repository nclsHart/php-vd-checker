<?php

interface EventInterface
{
    /**
     * @param string $name
     * @param callable $handler
     */
    public function listen(string $name, callable $handler) : void;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function fire(string $name) : bool;
}