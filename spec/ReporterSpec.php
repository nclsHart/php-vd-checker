<?php

namespace spec\PhpVdChecker;

use PhpSpec\ObjectBehavior;
use PhpVdChecker\Error;
use PhpVdChecker\Reporter;
use PhpVdChecker\Scope;

class ReporterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Reporter::class);
    }

    function it_has_no_error_by_default()
    {
        $this->countErrors()->shouldReturn(0);
    }

    function it_reports_error(Error $error, Scope $scope, \SplFileInfo $file)
    {
        $file->getPathname()->willReturn('test.php');
        $scope->getFile()->willReturn($file);

        $this->getErrorsForFile($file)->shouldReturn([]);

        $this->addError($error, $scope);
        $this->countErrors()->shouldReturn(1);
        $this->getErrorsForFile($file)->shouldReturn([$error]);
    }

}