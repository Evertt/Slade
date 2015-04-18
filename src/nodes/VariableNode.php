<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^=/
 */
class VariableNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections = null)
    {
        $newLines = countNewLines($node);
        $varName = static::strip($node);
        $var = $scope[$varName];

        if (starts_with($node, '==')) {
            return $var . repeat(PHP_EOL, $newLines);
        }

        return e($var) . repeat(PHP_EOL, $newLines);
    }
}
