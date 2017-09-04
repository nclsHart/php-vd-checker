<?php

namespace spec\PhpVdChecker\Command;

use PhpSpec\ObjectBehavior;
use PhpVdChecker\Command\CheckCommand;
use PhpVdChecker\Error;
use PhpVdChecker\NodeTraverser;
use PhpVdChecker\Reporter;
use PhpVdChecker\Scope;
use Prophecy\Argument;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class CheckCommandSpec extends ObjectBehavior
{
    function let(NodeTraverser $traverser, Reporter $reporter)
    {
        $this->beConstructedWith($traverser, $reporter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CheckCommand::class);
    }

    function it_needs_a_path_to_check()
    {
        $this->shouldThrow(RuntimeException::class)
            ->during('run', [new StringInput(''), new NullOutput()]);
    }

    function it_throws_an_exception_if_path_does_not_exist()
    {
        $this->shouldThrow(\RuntimeException::class)
            ->during('run', [
                new StringInput(__DIR__ . '/unexisting_path'),
                new NullOutput()
            ]);
    }

    function it_checks_all_files_in_path($traverser, $reporter)
    {
        $input = new StringInput(__DIR__ . '/../examples');

        $traverser->traverse(
            Argument::type('array'),
            Argument::that(function (Scope $scope) {
                return $scope->getFile()->getFilename() === 'Event.php';
            })
        )->shouldBeCalled();
        $traverser->traverse(
            Argument::type('array'),
            Argument::that(function (Scope $scope) {
                return $scope->getFile()->getFilename() === 'EventInterface.php';
            })
        )->shouldBeCalled();
        $reporter->countErrors()->willReturn(0);

        $this->run($input, new NullOutput());
    }

    function it_is_successful_when_there_is_no_error($reporter)
    {
        $input = new StringInput(__DIR__ . '/../examples/');

        $reporter->countErrors()->willReturn(0);

        $this->run($input, new NullOutput())->shouldReturn(0);
    }

    function it_is_failing_when_there_is_error($reporter)
    {
        $input = new StringInput(__DIR__ . '/../examples/');

        $reporter->countErrors()->willReturn(2);
        $reporter
            ->getErrorsForFile(Argument::that(function(\SplFileInfo $file) {
                return $file->getFilename() === 'Event.php';
            }))
            ->shouldBeCalled()
            ->willReturn([new Error(42, 'this is an error')]);
        $reporter
            ->getErrorsForFile(Argument::that(function (\SplFileInfo $file) {
                return $file->getFilename() === 'EventInterface.php';
            }))
            ->shouldBeCalled()
            ->willReturn([new Error(42, 'this is an error')]);

        $this->run($input, new NullOutput())->shouldReturn(1);
    }
}