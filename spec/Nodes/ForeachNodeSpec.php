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
            ->shouldBeLike(
                '<span>1</span>' . PHP_EOL .
                '<span>2</span>' . PHP_EOL .
                '<span>3</span>' . PHP_EOL
            );
    }

    function it_lets_the_developer_choose_their_own_variable_name(Scope $scope)
    {
        $block = new TemplateBlock('> rain > droplet' . PHP_EOL . '  span = droplet.size');

        $scope
            ->offsetGet('rain')
            ->willReturn([['size' => '1ml'],['size' => '5ml']]);

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike(
                '<span>1ml</span>' . PHP_EOL .
                '<span>5ml</span>' . PHP_EOL
            );
    }

    function it_lets_the_developer_choose_no_variable_name(Scope $scope)
    {
        $block = new TemplateBlock('> rain >' . PHP_EOL . '  span = size');

        $scope
            ->offsetGet('rain')
            ->willReturn([['size' => '1ml'],['size' => '5ml']]);

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike(
                '<span>1ml</span>' . PHP_EOL .
                '<span>5ml</span>' . PHP_EOL
            );

        $block = new TemplateBlock('> messages >' . PHP_EOL . '  span = self');

        $scope
            ->offsetGet('messages')
            ->willReturn(['Hello', 'World!']);

        $this
            ::parse($block, $scope, $scope)
            ->shouldBeLike(
                '<span>Hello</span>' . PHP_EOL .
                '<span>World!</span>' . PHP_EOL
            );
    }
}
