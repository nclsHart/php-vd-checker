<?php

namespace PhpVdChecker\Rule;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpVdChecker\Error;
use PhpVdChecker\Rule;

class NoDocumentationRule implements Rule
{
    /**
     * @param Node $node
     *
     * @return Error|null
     */
    public function check(Node $node):? Error
    {
        if (null === $node->getDocComment()) {
            return null;
        }

        return new Error($node->getLine(), sprintf('%s "%s" should not have documentation', $node->getType(), $node->name));
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function supports(Node $node): bool
    {
        return $node instanceof ClassLike || $node instanceof FunctionLike || $node instanceof PropertyProperty;
    }
}