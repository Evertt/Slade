<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class TagNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\TagNode');
    }

    function it_parses_doctypes(Scope $scope)
    {
        $block = new TemplateBlock('doctype html');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<!DOCTYPE html>');
    }

    function it_parses_lower_cased_text_as_html_tags(Scope $scope)
    {
        $block = new TemplateBlock('a');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<a></a>');

        $block = new TemplateBlock('my-component');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<my-component></my-component>');
    }

    function it_knows_about_self_closing_elements(Scope $scope)
    {
        $block = new TemplateBlock('meta');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<meta>');
    }

    function it_replaces_inline_variables(Scope $scope)
    {
        $block = new TemplateBlock('span = name');

        $scope->offsetGet('name')->willReturn('Evert');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<span>Evert</span>');

        $block = new TemplateBlock('p Hello, {{ name }}!');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<p>Hello, Evert!</p>');
    }

    function it_converts_hash_signs_to_id_attributes(Scope $scope)
    {
        $block = new TemplateBlock('#id');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<div id="id"></div>');
    }

    function it_converts_dot_signs_to_class_attributes(Scope $scope)
    {
        $block = new TemplateBlock('.first.second');

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<div class="first second"></div>');
    }
}
