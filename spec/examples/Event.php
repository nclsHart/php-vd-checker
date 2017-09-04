<?php

/**
 * Class Event
 */
final class Event implements EventInterface
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @param string $name
     * @param callable $handler
     */
    public function listen(string $name, callable $handler) : void
    {
        $this->events[$name][] = $handler;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function fire(string $name) : bool
    {
        if (! array_key_exists($name, $this->events)) {
            return false;
        }

        foreach ($this->events[$name] as $event) {
            $event();
        }

        return true;
    }
}