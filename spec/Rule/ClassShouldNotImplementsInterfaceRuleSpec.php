<?php

namespace spec\PhpVdChecker\Rule;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpSpec\ObjectBehavior;
use PhpVdChecker\Error;
use PhpVdChecker\Rule;
use PhpVdChecker\Rule\ClassShouldNotImplementsInterfaceRule;

class ClassShouldNotImplementsInterfaceRuleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ClassShouldNotImplementsInterfaceRule::class);
    }

    function it_implements_rule_interface(): void
    {
        $this->shouldImplement(Rule::class);
    }

    function it_supports_class_nodes(Class_ $classNode, Function_ $functionNode)
    {
        $this->supports($classNode)->shouldReturn(true);

        $this->supports($functionNode)->shouldReturn(false);
    }

    function it_checks_if_class_implements_an_interface(Class_ $node, Name $nodeName)
    {
        $node->implements = [];
        $this->check($node)->shouldReturn(null);

        $node->implements = [$nodeName];
        $node->getLine()->willReturn(42);
        $this->check($node)->shouldHaveType(Error::class);
    }
}