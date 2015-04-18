<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;

/**
 * @node /^_/
 */
class ExtendNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $file = static::getFilePath(static::strip($node));

        $infix = explode(PHP_EOL, $inner);

        $template = array_merge($infix, file($file));

        return Parser::parse($template, $scope, $sections);
    }
}
