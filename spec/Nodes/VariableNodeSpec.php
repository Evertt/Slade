<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class VariableNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\VariableNode');
    }

    function it_encodes_variables_when_prefixed_with_a_single_equals_sign(Scope $scope)
    {
        $block = new TemplateBlock('= body');
        $scope->offsetGet('body')->willReturn('<p>some text</p>');

        $this
            ::parse($block, $scope)->__toString()
            ->shouldBeLike('&lt;p&gt;some text&lt;/p&gt;');
    }

    function it_replaces_variables_literally_when_prefixed_with_a_double_equals_sign(Scope $scope)
    {
        $block = new TemplateBlock('== body');
        $scope->offsetGet('body')->willReturn('<p>some text</p>');

        $this
            ::parse($block, $scope)->__toString()
            ->shouldBeLike('<p>some text</p>');
    }
}
