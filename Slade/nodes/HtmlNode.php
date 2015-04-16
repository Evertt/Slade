<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^</
 */
class HtmlNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        $node .= PHP_EOL.$inner.PHP_EOL;

        static::replaceVars($node, $scope);

        return $node;
    }
}
