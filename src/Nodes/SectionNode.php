<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;
use Slade\TemplateBlock;

/**
 * @node /^@/
 */
class SectionNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $section = static::strip($block->getLine());

        $sections[$section] = $block->parseInsides($scope, $sections);
    }
}
