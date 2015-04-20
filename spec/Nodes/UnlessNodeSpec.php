<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;

class UnlessNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\UnlessNode');
    }

    function it_returns_null_if_provided_with_a_truthy(Scope $scope)
    {
        $scope->offsetGet('messages')->willReturn(3);

        $this
            ::parse('! messages', '| There are no messages for you.', 0, $scope, $scope)
            ->shouldBeNull();
    }

    function it_parses_insides_if_provided_with_a_falsy(Scope $scope)
    {
        $scope->offsetGet('messages')->willReturn(0);

        $this
            ::parse('! messages', '| There are no messages for you.', 0, $scope, $scope)
            ->shouldBe('There are no messages for you.');
    }
}
