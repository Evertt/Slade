<?php

namespace Slade\nodes;

use Slade\Slade;
use Slade\Scope;

/**
 * @node /^\+/
 */
class IncludeNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        $node = static::stripOperator($node);
        $parts = preg_split("/\s+(?=[^\t\r\n\f \/>\"\'=]+=(\"[^\"]+\"|\S+))/", $node);
        $file = str_replace('.', DIRECTORY_SEPARATOR, trim(array_shift($parts), "'")).'.slade';

        $data = [];

        foreach ($parts as $attribute) {
            $data += static::getAttribute($attribute, $scope);
        }

        $newScope = new Scope($data, $scope);

        return Slade::parse("templates/$file", $newScope);
    }

    protected static function getAttribute($attr, Scope $scope)
    {
        $m = [];

        if (static::matchAttribute($attr, static::$literal, $m)) {
            return [$m[1] => $m[2]];
        }

        if (static::matchAttribute($attr, static::$boolean, $m)) {
            return [$m[1] => $m[2] === 'true' ? true : false];
        }

        if (static::matchAttribute($attr, static::$variable, $m)) {
            return [$m[1] => $scope->get($m[2])];
        }
    }
}
