<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^\|/
 */
class TextNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope)
    {
        $node = substr($node.$inner,2);

        $node = static::replaceVars($node, $scope);

        return $node;
    }
}
