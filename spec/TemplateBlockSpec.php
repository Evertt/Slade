<?php

namespace spec\Slade;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TemplateBlockSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith("a href=\"#\" link\n");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\TemplateBlock');
    }

    function it_extracts_new_lines_from_block()
    {
        $this->getNewLines()->shouldReturn([0,1]);
    }
}
