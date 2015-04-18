<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^\|/
 */
class TextNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $node = substr($node.$inner,2);

        static::replaceVars($node, $scope);

        return $node;
    }
}
