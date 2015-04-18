<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;

/**
 * @node /^-/
 */
class YieldNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $newLines = countNewLines($node.$inner);

        $section = static::strip($node);

        $html = $sections[$section];

        if ($html)
        {
            return $html . repeat(PHP_EOL, $newLines);
        }
        else
        {
            return Parser::parse($inner, $scope, $sections);
        }
    }
}
