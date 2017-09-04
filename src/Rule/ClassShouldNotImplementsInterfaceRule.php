<?php

namespace PhpVdChecker\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpVdChecker\Error;
use PhpVdChecker\Rule;

class ClassShouldNotImplementsInterfaceRule implements Rule
{
    /**
     * @param Node $node
     *
     * @return Error|null
     */
    public function check(Node $node):? Error
    {
        if (empty($node->implements)) {
            return null;
        }

        return new Error($node->getLine(), sprintf('Class "%s" should not implement any interface.', $node->name));
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function supports(Node $node): bool
    {
        return $node instanceof Class_;
    }
}