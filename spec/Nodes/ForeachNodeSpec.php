<?php

namespace spec\Slade\Nodes;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slade\Scope;
use Slade\TemplateBlock;

class ForeachNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Nodes\ForeachNode');
    }

    function it_repeats_its_children_for_an_iterable(Scope $scope)
    {
        $block = new TemplateBlock('> indices' . PHP_EOL . '  span = index.id');

        $scope
            ->offsetGet('indices')
            ->willReturn([['id' => 1], ['id' => 2], ['id' => 3]]);

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike('<span>1</span><span>2</span><span>3</span>');
    }
}
