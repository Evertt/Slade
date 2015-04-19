<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ForeachNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\ForeachNode');
    }
}
