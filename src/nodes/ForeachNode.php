<?php

namespace Slade\nodes;

use Slade\Parser;
use Slade\Scope;

/**
 * @node /^>/
 */
class ForeachNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $var = static::strip($node);

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
