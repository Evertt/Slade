<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class SectionNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\SectionNode');
    }

    function it_parses_its_children_and_assigns_them_to_a_section(Scope $scope, Scope $sections)
    {
        $block = new TemplateBlock('@ content' . PHP_EOL . '  h1 My blog');

        $this::parse($block, $scope, $sections);

        $sections->offsetSet('content', '<h1>My blog</h1>')->shouldHaveBeenCalled();
    }

    function it_assigns_inline_text_to_a_section(Scope $scope, Scope $sections)
    {
        $block = new TemplateBlock('@ title My page title');

        $this::parse($block, $scope, $sections);

        $sections->offsetSet('title', 'My page title')->shouldHaveBeenCalled();
    }

    function it_assigns_an_inline_variable_to_a_section(Scope $scope, Scope $sections)
    {
        $block = new TemplateBlock('@ title = title');

        $scope->offsetGet('title')->willReturn('My page title');

        $this::parse($block, $scope, $sections);

        $sections->offsetSet('title', 'My page title')->shouldHaveBeenCalled();
    }
}
