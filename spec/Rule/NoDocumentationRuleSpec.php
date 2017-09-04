<?php

namespace spec\PhpVdChecker\Rule;

use PhpParser\Comment\Doc;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\Stmt\Return_;
use PhpSpec\ObjectBehavior;
use PhpVdChecker\Error;
use PhpVdChecker\Rule;
use PhpVdChecker\Rule\NoDocumentationRule;

class NoDocumentationRuleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NoDocumentationRule::class);
    }

    function it_implements_rule_interface(): void
    {
        $this->shouldImplement(Rule::class);
    }

    function it_supports_class_function_and_property_nodes(
        ClassLike $classNode,
        FunctionLike $functionNode,
        PropertyProperty $propertyNode,
        Return_ $anotherNode
    )
    {
        $this->supports($classNode)->shouldReturn(true);
        $this->supports($functionNode)->shouldReturn(true);
        $this->supports($propertyNode)->shouldReturn(true);

        $this->supports($anotherNode)->shouldReturn(false);
    }

    function it_checks_if_node_has_documentation(ClassLike $nodeWithDocumentation, Doc $documentation, ClassLike $nodeWithoutDocumentation)
    {
        $nodeWithDocumentation->getDocComment()->willReturn($documentation);
        $nodeWithDocumentation->getLine()->willReturn(42);
        $nodeWithDocumentation->getType()->willReturn('Stmt_Class');
        $this->check($nodeWithDocumentation)->shouldhaveType(Error::class);

        $nodeWithoutDocumentation->getDocComment()->willReturn(null);
        $this->check($nodeWithoutDocumentation)->shouldReturn(null);
    }
}