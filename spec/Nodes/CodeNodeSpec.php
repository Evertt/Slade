<?php

namespace spec\Slade\Nodes;

use Slade\Scope;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\TemplateBlock;

class CodeNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\CodeNode');
    }

    function it_parses_css_code(Scope $scope)
    {
        $block = new TemplateBlock('css: body {color: #333;}');

        $this
            ::parse($block, $scope)
            ->shouldBeLike('<style>body {color: #333;}</style>');
    }

    function it_parses_javascript_code(Scope $scope)
    {
        $block = new TemplateBlock('javascript: console.log("test");');

        $this
            ::parse($block, $scope)
            ->shouldBeLike('<script>console.log("test");</script>');
    }

    function it_replaces_variables(Scope $scope)
    {
        $scope->offsetGet('message')->willReturn('Hello World!');
        $block = new TemplateBlock('javascript: console.log("{{ message }}");');

        $this
            ::parse($block, $scope)
            ->shouldBeLike('<script>console.log("Hello World!");</script>');
    }

    function it_leaves_escaped_variables(Scope $scope)
    {
        $scope->offsetGet('message')->willReturn('Hello World!');
        $block = new TemplateBlock('javascript: console.log("\{{ message }}");');

        $this
            ::parse($block, $scope)
            ->shouldBeLike('<script>console.log("{{ message }}");</script>');
    }
}
