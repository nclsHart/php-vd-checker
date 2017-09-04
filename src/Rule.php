<?php

namespace PhpVdChecker;

use PhpParser\Node;

interface Rule
{
    /**
     * @param Node $node
     *
     * @return Error|null
     */
    public function check(Node $node):? Error;

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function supports(Node $node): bool;
}