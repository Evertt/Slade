<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^\|/
 */
class TextNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        $node = static::format($node.PHP_EOL.$inner);

        static::replaceVars($node, $scope);

        return rtrim($node, PHP_EOL).PHP_EOL;
    }

    protected static function format($inner)
    {
        $lines = explode(PHP_EOL, $inner);

        $removeIndentation = function (&$line) {
            $line = substr($line, 2);
        };

        array_walk($lines, $removeIndentation);

        return implode(PHP_EOL, $lines);
    }
}
