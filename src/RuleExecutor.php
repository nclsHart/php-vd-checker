<?php

namespace PhpVdChecker;

use PhpParser\Node;

class RuleExecutor
{
    /**
     * @var Reporter
     */
    private $reporter;

    /**
     * @var Rule[]
     */
    private $rules;

    /**
     * @param array $rules
     * @param Reporter $reporter
     */
    public function __construct(array $rules, Reporter $reporter)
    {
        $this->rules = $rules;
        $this->reporter = $reporter;
    }

    /**
     * @param Node $node
     * @param Scope $scope
     */
    public function checkNode(Node $node, Scope $scope)
    {
         foreach ($this->rules as $rule) {
             if ($rule->supports($node)) {
                 $error = $rule->check($node);

                 if (null !== $error) {
                     $this->reporter->addError($error, $scope);
                 }
             }
         }
    }
}