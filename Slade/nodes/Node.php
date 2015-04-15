<?php namespace Slade\Nodes;

use Slade\Parser;
use Slade\Scope;

abstract class Node {

    protected $node;
    protected $scope;
    protected $inner;

    public static function parse($node, $inner, Scope &$scope, Scope &$sections) {
        $class = explode('\\', static::class);
        $class = end($class);
        return "<to-do $class>" . rtrim(Parser::parse($inner, $scope), PHP_EOL) . '</to-do>' . PHP_EOL;
    }

    protected static function stripOperator($node) {
        return ltrim($node, $node[0] . ' ');
    }

}