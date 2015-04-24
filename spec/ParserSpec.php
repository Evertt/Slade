<?php

namespace spec\Slade;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Parser');
    }

    function it_should_return_nothing_when_given_nothing()
    {
        static::parse()->shouldReturn('');
    }
}
