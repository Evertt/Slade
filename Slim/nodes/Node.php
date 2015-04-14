<?php namespace Slim\Nodes;

use Slim\Parser;
use Slim\Scope;

abstract class Node {

    protected $node;
	protected $scope;
	protected $inner;

    public static function parse($node, Scope $scope, $inner) {
        $class = explode('\\', static::class);
        $class = end($class);
        return "<to-do $class>" . rtrim(Parser::parse($inner, $scope), PHP_EOL) . '</to-do>' . PHP_EOL;
    }

    protected static function stripOperator($operator, $node) {
        return ltrim($node, "$operator ");
    }

}