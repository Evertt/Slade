<?php namespace Slim\Nodes;

use Slim\Parser;
use Slim\Scope;

/**
 * @node /^!/
 */
class UnlessNode extends Node {

    public static function parse($node, Scope $scope, $inner) {
        $var = static::stripOperator('!', $node);

        if (!$scope->get($var))
            return Parser::parse($inner, $scope);
    }

}