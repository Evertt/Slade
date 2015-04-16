<?php

namespace Slade\nodes;

use Slade\Parser;
use Slade\Scope;

/**
 * @node /^>/
 */
class ForeachNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        $var = static::stripOperator($node);
        $itemName = rtrim($var, 's');
        $html = '';

        foreach ($scope->get($var, []) as $item) {
            $html .= Parser::parse(
                $inner,
                new Scope([$itemName => $item], $scope),
                $sections
            );
        }

        return $html;
    }
}
