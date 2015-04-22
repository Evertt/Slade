<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^\|/
 */
class TextNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $line = static::strip($block->getLine());

        $line .= $block->getInsides();

        $line = static::replaceVars($line, $scope, $sections);

        return $line;
    }
}
