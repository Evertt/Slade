<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;

/**
 * @node /^@/
 */
class SectionNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        $section = static::stripOperator($node);

        $sections->set($section, Parser::parse($inner, $scope, $sections));
    }
}
