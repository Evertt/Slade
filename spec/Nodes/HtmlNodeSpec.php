<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class HtmlNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\HtmlNode');
    }

    function it_leaves_plain_html_as_is(Scope $scope)
    {
        $block = new TemplateBlock('<a></a>');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<a></a>');
    }

    function it_replaces_variables(Scope $scope)
    {
        $block = new TemplateBlock('<span>{{author}}</span>');
        $scope->offsetGet('author')->willReturn('Evert');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<span>Evert</span>');
    }
}
