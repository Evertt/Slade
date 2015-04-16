<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^-/
 */
class YieldNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        $section = static::stripOperator($node);

        return $sections->get($section);
    }
}
