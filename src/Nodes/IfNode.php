<?php

namespace Slade\nodes;

use Slade\Parser;
use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^\?/
 */
class IfNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $var = static::strip($block->getLine());

        if ($scope[$var]) {
            return $block->parseInsides($scope, $sections);
        }
    }
}
