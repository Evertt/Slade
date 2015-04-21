<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;
use Slade\TemplateBlock;

/**
 * @node /^-/
 */
class YieldNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $section = static::strip($block->getLine());

        if ($insides = $sections[$section])
        {
            $block->setInsides($insides);

            $block->removeLine();

            return $block;
        }

        return $block->parseInsides($scope, $sections);
    }
}
