<?php namespace Slade\Nodes;

use Slade\Scope;
use Slade\Parser;

/**
 * @node /^@/
 */
class SectionNode extends Node {

    public static function parse($node, $inner, Scope &$scope, Scope &$sections) {
        $section = trim(static::stripOperator($node), "'");
        $sections->set($section, Parser::parse($inner, $scope, $sections));
    }

}