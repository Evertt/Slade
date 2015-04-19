<?php

namespace spec\Slade\Nodes;

use Slade\Scope;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CodeNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\CodeNode');
    }

    function it_should_parse_css_code(Scope $scope)
    {
        $this
            ::parse('css: body {color: #333;}', '', 0, $scope)
            ->shouldBe('<style>body {color: #333;}</style>');
    }

    function it_should_parse_javascript_code(Scope $scope)
    {
        $this
            ::parse('javascript: console.log("test");', '', 0, $scope)
            ->shouldBe('<script>console.log("test");</script>');
    }

    function it_should_replace_variables(Scope $scope)
    {
        $scope->offsetGet('message')->willReturn('Hello World!');

        $this
            ::parse('javascript: console.log("{{ message }}");', '', 0, $scope)
            ->shouldBe('<script>console.log("Hello World!");</script>');
    }
}
