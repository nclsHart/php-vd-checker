<?php

namespace PhpVdChecker\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use PhpVdChecker\Error;
use PhpVdChecker\Rule;

class InterfaceShouldNotExistRule implements Rule
{
    /**
     * @param Node $node
     *
     * @return Error|null
     */
    public function check(Node $node):? Error
    {
        return new Error($node->getLine(), sprintf('Interface "%s" should be removed.', $node->name));
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function supports(Node $node): bool
    {
        return $node instanceof Interface_;
    }
}