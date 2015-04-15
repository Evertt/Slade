<?php namespace Slade\Nodes;

use Slade\Scope;

/**
 * @node /^-/
 */
class YieldNode extends Node {

    public static function parse($node, $inner, Scope &$scope, Scope &$sections) {
        $section = trim(static::stripOperator($node), "'");
        return $sections->get($section);
    }

}