<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^</
 */
class HtmlNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $block->indentInsides();

        $line = $block->getLine();
        $insides = $block->getInsides();

        $block->setLine(static::replaceVars($line, $scope, $sections));
        $block->setInsides(static::replaceVars($insides, $scope, $sections));

        return $block;
    }
}
