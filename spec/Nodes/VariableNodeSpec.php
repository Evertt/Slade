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

        static
            ::parse($block, $scope)->__toString()
            ->shouldBeLike('&lt;p&gt;some text&lt;/p&gt;');
    }

    function it_replaces_variables_literally_when_prefixed_with_a_double_equals_sign(Scope $scope)
    {
        $block = new TemplateBlock('== body');
        $scope->offsetGet('body')->willReturn('<p>some text</p>');

        static
            ::parse($block, $scope)
            ->shouldBeLike('<p>some text</p>');
    }

    function it_executes_functions(Scope $scope)
    {
        $block = new TemplateBlock('= str_repeat("ha", user.laughs)');
        $scope->offsetGet('user.laughs')->willReturn(3);

        static
            ::parse($block, $scope)
            ->shouldBeLike('hahaha');
    }
}
