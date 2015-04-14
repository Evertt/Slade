<?php namespace Slim\Nodes;

use Slim\Parser;
use Slim\Scope;

/**
 * @node /^>/
 */
class ForeachNode extends Node {

    public static function parse($node, Scope $scope, $inner) {
        $var = static::stripOperator('>', $node);
        $itemName = rtrim($var, 's');
        $html = '';

        foreach($scope->get($var, []) as $item)
            $html .= Parser::parse($inner, new Scope([$itemName => $item], $scope));
        
        return $html;
    }

}