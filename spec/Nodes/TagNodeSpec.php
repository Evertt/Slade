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

    function it_should_parse_doctype_correctly(Scope $scope)
    {
        $this
            ::parse('doctype html', '', 0, $scope, $scope)
            ->shouldReturn('<!DOCTYPE html>');
    }

    function it_should_parse_lower_cased_text_as_an_html_tag(Scope $scope)
    {
        $this
            ::parse('a', '', 0, $scope, $scope)
            ->shouldReturn('<a></a>');
    }
}
