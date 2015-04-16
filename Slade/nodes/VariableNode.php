<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^=/
 */
class VariableNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections = null)
    {
        $varName = static::stripOperator($node);
        $var = $scope->get($varName);

        if (substr($node, 0, 2) == '==') {
            return $var.PHP_EOL;
        }

        return he($var).PHP_EOL;
    }
}
