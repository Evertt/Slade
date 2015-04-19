<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;

class VariableNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\VariableNode');
    }

    function it_should_encode_variables_when_prefixed_with_a_single_equals_sign(Scope $scope)
    {
        $scope->offsetGet('body')->willReturn('<p>some text</p>');

        $this
            ::parse('= body', '', 0, $scope)
            ->shouldReturn('&lt;p&gt;some text&lt;/p&gt;');
    }

    function it_should_replace_variables_literally_when_prefixed_with_a_double_equals_sign(Scope $scope)
    {
        $scope->offsetGet('body')->willReturn('<p>some text</p>');

        $this
            ::parse('== body', '', 0, $scope)
            ->shouldReturn('<p>some text</p>');
    }
}
