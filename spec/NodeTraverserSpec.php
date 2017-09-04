<?php

namespace spec\PhpVdChecker;

use PhpParser\Node;
use PhpParser\ParserFactory;
use PhpSpec\ObjectBehavior;
use PhpVdChecker\NodeTraverser;
use PhpVdChecker\RuleExecutor;
use PhpVdChecker\Scope;
use Prophecy\Argument;

class NodeTraverserSpec extends ObjectBehavior
{
    function let(RuleExecutor $ruleExecutor)
    {
        $this->beConstructedWith($ruleExecutor);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NodeTraverser::class);
    }

    function it_traverse_a_node($ruleExecutor, Scope $scope)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $nodes = $parser->parse(
<<<CODE
<?php

class Test {
    function methodA() {}
    function methodB() {}
}
CODE
        );

        $ruleExecutor->checkNode(Argument::type(Node::class), $scope)->shouldBeCalledTimes(3);

        $this->traverse($nodes, $scope);
    }
}