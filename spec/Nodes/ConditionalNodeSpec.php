<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class ConditionalNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\ConditionalNode');
    }

    function it_returns_null_if_provided_with_a_falsy(Scope $scope)
    {
        $block = new TemplateBlock(
            '? messages' . PHP_EOL . ' | You have {{ messages }} messages!'
        );

        $scope->offsetGet('messages')->willReturn(0);

        static
            ::parse($block, $scope, $scope)
            ->shouldBeNull();
    }

    function it_parses_insides_if_provided_with_a_truthy(Scope $scope)
    {
        $block = new TemplateBlock(
            '? messages' . PHP_EOL . ' | You have {{ messages }} messages!'
        );

        $scope->offsetGet('messages')->willReturn(3);

        static
            ::parse($block, $scope, $scope)
            ->shouldBeLike('You have 3 messages!');
    }

    function it_works_with_inverted_conditionals(Scope $scope)
    {
        $block = new TemplateBlock(
            '! messages' . PHP_EOL . ' | You have no messages...'
        );

        $scope->offsetGet('messages')->willReturn(0);

        static
            ::parse($block, $scope, $scope)
            ->shouldBeLike('You have no messages...');
    }

    function it_processes_more_complex_if_statements(Scope $scope)
    {
        $block = new TemplateBlock(
            "? messages && user.role == 'administrator'" . PHP_EOL .
            '  | You have {{ messages }} messages!'
        );

        $scope->offsetGet('messages')->willReturn(3);
        $scope->offsetGet('user.role')->willReturn('administrator');

        static
            ::parse($block, $scope, $scope)
            ->shouldBeLike('You have 3 messages!');
    }
}
