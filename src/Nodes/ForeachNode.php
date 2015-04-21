<?php

namespace Slade\nodes;

use Slade\Parser;
use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^>/
 */
class ForeachNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $inner = $block->getInsides() . repeat(PHP_EOL, $block->getNewLines()[1]);

        $var = $block->stripLine();

        $varName = substr($var, (strrpos($var, '.') ?: -1) + 1);

        $itemName = str_singular($varName);

        $html = '';

        foreach ($scope[$var] ?: [] as $item) {
            $html .= Parser::parse(
                $inner,
                new Scope([$itemName => $item], $scope),
                $sections
            );
        }

        return $html;
    }
}
