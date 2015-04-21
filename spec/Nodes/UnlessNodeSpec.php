<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class UnlessNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\UnlessNode');
    }

    function it_returns_null_if_provided_with_a_truthy(Scope $scope)
    {
        $block = new TemplateBlock(
            '! messages' . PHP_EOL . ' | You have no messages.'
        );
        $scope->offsetGet('messages')->willReturn(3);

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeNull();
    }

    function it_parses_insides_if_provided_with_a_falsy(Scope $scope)
    {
        $block = new TemplateBlock(
            '! messages' . PHP_EOL . ' | You have no messages.'
        );
        $scope->offsetGet('messages')->willReturn(0);

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('You have no messages.');
    }
}
