<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommentNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\CommentNode');
    }

    function it_returns_null_when_provided_with_a_slade_comment()
    {
        $this::parse('/ So this should return null', '', 0)->shouldBeNull();
    }

    function it_parses_an_inline_html_comment()
    {
        $this::parse('/! This should be an HTML comment', '', 0)
                ->shouldBe('<!-- This should be an HTML comment -->');
    }

    function it_parses_an_html_comment_block()
    {
        $this::parse("/!\n", 'This should be an HTML comment', 0)
                ->shouldBe("<!--  \nThis should be an HTML comment\n-->");
    }
}
