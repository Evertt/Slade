<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\TemplateBlock;

class CommentNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\CommentNode');
    }

    function it_returns_null_when_provided_with_a_slade_comment()
    {
        $block = new TemplateBlock('/ So this returns null');

        static::parse($block)->shouldBeNull();
    }

    function it_parses_an_inline_html_comment()
    {
        $block = new TemplateBlock('/! This is an inline HTML comment');

        static::parse($block)
            ->shouldBeLike('<!-- This is an inline HTML comment -->');
    }

    function it_parses_an_html_comment_block()
    {
        $block = new TemplateBlock('/!' . PHP_EOL . '  This an HTML block comment');

        static::parse($block)
            ->shouldBeLike('<!-- ' . PHP_EOL . '  This an HTML block comment' . PHP_EOL . ' -->');
    }
}
