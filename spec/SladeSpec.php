<?php

namespace spec\Slade;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SladeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Slade');
    }
}
