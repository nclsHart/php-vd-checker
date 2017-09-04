<?php

namespace PhpVdChecker;

use PhpParser\Node;

class NodeTraverser
{
    /**
     * @var RuleExecutor
     */
    private $ruleExecutor;

    /**
     * @var Scope
     */
    private $scope;

    /**
     * @param RuleExecutor $ruleExecutor
     */
    public function __construct(RuleExecutor $ruleExecutor)
    {
        $this->ruleExecutor = $ruleExecutor;
    }

    /**
     * @param Node[] $nodes
     * @param Scope $scope
     */
    public function traverse(array $nodes, Scope $scope)
    {
        $this->scope = $scope;

        foreach ($nodes as $node) {
            $this->traverseNode($node);
        }
    }

    /**
     * @param Node $node
     */
    private function traverseNode(Node $node)
    {
        $this->ruleExecutor->checkNode($node, $this->scope);

        foreach ($node->getSubNodeNames() as $name) {
            $subNode =& $node->$name;

            if ($subNode instanceof Node) {
                $this->traverseNode($subNode);
            } elseif (is_array($subNode)) {
                $this->traverseArray($subNode);
            }
        }
    }

    /**
     * @param array $nodes
     */
    private function traverseArray(array $nodes)
    {
        foreach($nodes as $node) {
            if ($node instanceof Node) {
                $this->traverseNode($node);
            } elseif (is_array($node)) {
                $this->traverseArray($node);
            }
        }
    }
}