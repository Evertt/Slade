<?php

namespace Slade\nodes;

use Slade\Parser;
use Slade\Scope;

/**
 * @node /^!/
 */
class UnlessNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $var = static::strip($node);

        if (!$scope[$var]) {
            return Parser::parse($inner, $scope);
        }
    }
}
