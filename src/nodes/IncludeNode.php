<?php

namespace Slade\nodes;

use Slade\Slade;
use Slade\Scope;

/**
 * @node /^\+/
 */
class IncludeNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $newLines = countNewLines($node.$inner);

        $node = static::strip($node);

        $file = strtok($node, " \r\n");

        $data = static::getAttributes($node, $scope)['array'];

        $newScope = new Scope($data, $scope);

        $parsed = Slade::parse($file, $newScope);

        return trim($parsed) . str_repeat(PHP_EOL, $newLines);
    }
}
