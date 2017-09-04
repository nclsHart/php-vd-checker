<?php

namespace spec\PhpVdChecker;

use PhpParser\Node;
use PhpSpec\ObjectBehavior;
use PhpVdChecker\Error;
use PhpVdChecker\Reporter;
use PhpVdChecker\Rule;
use PhpVdChecker\RuleExecutor;
use PhpVdChecker\Scope;
use Prophecy\Argument;

class RuleExecutorSpec extends ObjectBehavior
{
    function let(Rule $ruleWithError, Rule $ruleWithoutError, Reporter $reporter)
    {
        $this->beConstructedWith([$ruleWithError, $ruleWithoutError], $reporter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RuleExecutor::class);
    }

    function it_checks_a_node_against_rules(
        $ruleWithError,
        $ruleWithoutError,
        $reporter,
        Node $node,
        Scope $scope,
        Error $error
    )
    {
        $ruleWithError->supports($node)->willReturn(true);
        $ruleWithError->check($node)->shouldBeCalled()->willReturn($error);
        $reporter->addError($error, $scope)->shouldBeCalled();

        $ruleWithoutError->supports($node)->willReturn(true);
        $ruleWithoutError->check($node)->shouldBeCalled()->willReturn(null);
        $reporter->addError(Argument::cetera())->shouldBeCalledTimes(1);

        $this->checkNode($node, $scope);
    }

    function it_does_not_check_node_if_rule_not_supports_it(Rule $rule, Reporter $reporter, Node $node, Scope $scope)
    {
        $this->beConstructedWith([$rule], $reporter);

        $rule->supports($node)->willReturn(false);
        $rule->check(Argument::cetera())->shouldNotBeCalled();

        $this->checkNode($node, $scope);
    }
}