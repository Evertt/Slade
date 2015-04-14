<?php namespace Slade\Nodes;

use Slade\Scope;

/**
 * @node /^=/
 */
class VariableNode extends Node {

    public static function parse($node, Scope $scope, $inner) {
        $varName = static::stripOperator('=', $node);
        $var = $scope->get($varName);

        if (substr($node, 0, 2) == '==')
            return $var . PHP_EOL;

        else
            return he($var) . PHP_EOL;               
    }

}