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
        $var = $block->stripLine();
        $block->removeLine();

        $varName = substr($var, (strrpos($var, '.') ?: -1) + 1);

        $itemName = str_singular($varName);

        $html = '';

        foreach ($scope[$var] ?: [] as $item) {
            $html .= Parser::parse(
                $block . PHP_EOL,
                new Scope([$itemName => $item], $scope),
                $sections
            );
        }

        return finish($html, PHP_EOL);
    }
}
