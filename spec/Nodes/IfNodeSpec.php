<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;

class IfNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\IfNode');
    }

    function it_should_return_null_if_provided_with_a_falsy(Scope $scope)
    {
        $scope->offsetGet('items')->willReturn('');

        $this::parse('? items', '', 0, $scope, $scope)->shouldBeNull();
    }

    function it_should_parse_inner_if_provided_with_a_truthy(Scope $scope)
    {
        $scope->offsetGet('items')->willReturn([1,2,3]);

        $this
            ::parse('? items', '<p>returned</p>', 0, $scope, $scope)
            ->shouldBe('<p>returned</p>');
    }
}
