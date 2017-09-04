<?php

namespace spec\PhpVdChecker\Rule;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpSpec\ObjectBehavior;
use PhpVdChecker\Error;
use PhpVdChecker\Rule;
use PhpVdChecker\Rule\InterfaceShouldNotExistRule;

class InterfaceShouldNotExistRuleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InterfaceShouldNotExistRule::class);
    }

    function it_implements_rule_interface(): void
    {
        $this->shouldImplement(Rule::class);
    }

    function it_supports_interface_nodes(Interface_ $interfaceNode, Class_ $classNode)
    {
        $this->supports($interfaceNode)->shouldReturn(true);

        $this->supports($classNode)->shouldReturn(false);
    }

    function it_checks_if_node_is_an_interface(Interface_ $node)
    {
        $node->getLine()->willReturn(42);

        $this->check($node)->shouldhaveType(Error::class);
    }
}