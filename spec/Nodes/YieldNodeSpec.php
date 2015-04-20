<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;

class YieldNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\YieldNode');
    }

    function it_should_yield_a_section(Scope $sections)
    {
        $sections->offsetGet('content')->willReturn('<p>Hello World!</p>');

        $this
            ::parse('- content', '<p>This is default content.</p>', 0, $sections, $sections)
            ->shouldReturn('<p>Hello World!</p>');
    }

    function it_should_parse_default_content_if_a_section_does_not_exist(Scope $sections)
    {
        $sections->offsetGet('content')->willReturn(null);

        $this
            ::parse('- content', '<p>This is default content.</p>', 0, $sections, $sections)
            ->shouldReturn('<p>This is default content.</p>');
    }
}
