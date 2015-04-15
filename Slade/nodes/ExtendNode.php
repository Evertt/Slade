<?php namespace Slade\Nodes;

use Slade\Slade;
use Slade\Scope;
use Slade\Parser;

/**
 * @node /^_/
 */
class ExtendNode extends Node {

    public static function parse($node, $inner, Scope &$scope, Scope &$sections) {
        $file = 'templates/' . str_replace('.', '/', trim(static::stripOperator($node), "'")) . '.slade';
        $depth = Parser::getDepth(explode(PHP_EOL, $inner)[0]);
        $inner = static::format($inner, $depth);
        return Parser::parse(array_merge(explode(PHP_EOL, $inner), Slade::retrieveFile($file)), $scope, $sections);
    }

    protected static function format($inner, $d) {
        return implode(
            PHP_EOL,
            array_map(
                function($l) use ($d) {
                    return substr($l, $d);
                },
                array_values(array_filter(explode(PHP_EOL, $inner), 'trim'))
            )
        );
    }

}