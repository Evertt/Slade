<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class YieldNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\YieldNode');
    }

    function it_should_yield_a_section(Scope $sections)
    {
        $block = new TemplateBlock(
            '- content' . PHP_EOL .
            '  <p>This is default content.</p>'
        );
        $sections->offsetGet('content')->willReturn('<p>Hello World!</p>');

        static
            ::parse($block, $sections, $sections)
            ->shouldBeLike('<p>Hello World!</p>');
    }

    function it_should_parse_default_content_if_a_section_does_not_exist(Scope $sections)
    {
        $block = new TemplateBlock(
            '- content' . PHP_EOL .
            '  <p>This is default content.</p>'
        );
        $sections->offsetGet('content')->willReturn(null);

        static
            ::parse($block, $sections, $sections)
            ->shouldBeLike('<p>This is default content.</p>');
    }
}
