<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;

class HtmlNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\HtmlNode');
    }

    function it_should_leave_plain_html_as_is(Scope $scope)
    {
        $this
            ::parse('<a></a>', '', 0, $scope, $scope)
            ->shouldReturn('<a></a>');
    }

    function it_should_replace_variables(Scope $scope)
    {
        $scope->offsetGet('author')->willReturn('Evert');

        $this
            ::parse('<span>{{author}}</span>', '', 0, $scope, $scope)
            ->shouldReturn('<span>Evert</span>');
    }
}
