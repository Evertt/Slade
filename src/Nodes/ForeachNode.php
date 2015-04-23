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
        $line = $block->getLine();
        $vars = preg_split('/\s*>\s*/', $block->stripLine());
        $block->removeLine();

        $var = $vars[0];
        $varName = substr($var, (strrpos($var, '.') ?: -1) + 1);

        if (isset($vars[1]))
            $itemName = $vars[1];

        elseif (preg_match('/>\s*$/', $line))
            $itemName = 'self';

        else
            $itemName = singular($varName);

        $html = '';

        foreach ($scope[$var] ?: [] as $item) {
            $html .= Parser::parse(
                $block . PHP_EOL,
                new Scope(
                    $itemName == 'self' && is_array($item)
                        ? $item : [$itemName => $item],
                    $scope
                ),
                $sections
            );
        }

        return finish($html, PHP_EOL);
    }
}
