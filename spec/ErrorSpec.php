<?php

namespace spec\PhpVdChecker;

use PhpSpec\ObjectBehavior;
use PhpVdChecker\Error;

class ErrorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(42, 'this is an error');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Error::class);
    }

    function it_returns_line()
    {
        $this->getLine()->shouldReturn(42);
    }

    function it_returns_message()
    {
        $this->getMessage()->shouldReturn('this is an error');
    }
}