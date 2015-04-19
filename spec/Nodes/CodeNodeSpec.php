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
        $node = "css:\n";
        $code = "body {color: #333;}\n";
        $depth = 2;

        $result =
            '<style>' . str_repeat(' ', $depth) . PHP_EOL .
            str_repeat(' ', $depth) . rtrim($code) . PHP_EOL .
            '</style>' . PHP_EOL;

        $this
            ::parse($node, $code, $depth, $scope, $scope)
            ->shouldBe($result);
    }

    function it_should_parse_javascript_code(Scope $scope)
    {
        $node = "javascript:\n";
        $code = "console.log('test');\n";
        $depth = 2;

        $result =
            '<script>' . str_repeat(' ', $depth) . PHP_EOL .
            str_repeat(' ', $depth) . rtrim($code) . PHP_EOL .
            '</script>' . PHP_EOL;

        $this
            ::parse($node, $code, $depth, $scope, $scope)
            ->shouldBe($result);
    }
}
