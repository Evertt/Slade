<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;

/**
 * @node /^@/
 */
class SectionNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $section = static::strip($node);

        $sections[$section] = Parser::parse($inner, $scope, $sections);
    }
}
