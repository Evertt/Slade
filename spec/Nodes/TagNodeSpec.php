<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;

class TagNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\TagNode');
    }

    function it_parses_doctypes(Scope $scope)
    {
        $this
            ::parse('doctype html', '', 0, $scope, $scope)
            ->shouldReturn('<!DOCTYPE html>');
    }

    function it_parses_lower_cased_text_as_html_tags(Scope $scope)
    {
        $this
            ::parse('a', '', 0, $scope, $scope)
            ->shouldReturn('<a></a>');

        $this
            ::parse('my-component', '', 0, $scope, $scope)
            ->shouldReturn('<my-component></my-component>');
    }

    function it_knows_about_self_closing_elements(Scope $scope)
    {
        $this
            ::parse('meta', '', 0, $scope, $scope)
            ->shouldReturn('<meta>');
    }

    function it_replaces_inline_variables(Scope $scope)
    {
        $scope->offsetGet('name')->willReturn('Evert');

        $this
            ::parse('span = name', '', 0, $scope, $scope)
            ->shouldReturn('<span>Evert</span>');

        $this
            ::parse('p Hello, {{ name }}!', '', 0, $scope, $scope)
            ->shouldReturn('<p>Hello, Evert!</p>');
    }
}
