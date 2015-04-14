<?php namespace Slade\Nodes;

use Slade\Parser;
use Slade\Scope;

/**
 * @node /^\?/
 */
class IfNode extends Node {

    public static function parse($node, Scope $scope, $inner) {
        $var = static::stripOperator('?', $node);

        if ($scope->get($var))
            return Parser::parse($inner, $scope);
    }
    
}