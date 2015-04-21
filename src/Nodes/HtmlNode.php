<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^</
 */
class HtmlNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope)
    {
        $block->indentInsides();

        $line = $block->getLine() . $block->getInsides();

        $block->setLine(static::replaceVars($line, $scope));

        return $block;
    }
}
