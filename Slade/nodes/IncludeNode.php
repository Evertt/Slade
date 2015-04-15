<?php namespace Slade\Nodes;

use Slade\Slade;
use Slade\Scope;

/**
 * @node /^\+/
 */
class IncludeNode extends Node {

    public static function parse($node, $inner, Scope &$scope, Scope &$sections) {
        $node = static::stripOperator($node);
        $parts = preg_split("/\s+(?=[^\t\r\n\f \/>\"\'=]+=(\"[^\"]+\"|\S+))/", $node);
        $file = str_replace('.', DIRECTORY_SEPARATOR, trim(array_shift($parts), "'")) . '.slade';

        $data = [];

        foreach($parts as $attribute)
            $data += static::getAttribute($attribute, $scope);

        $newScope = new Scope($data, $scope);
        return Slade::parse("templates/$file", $newScope);
    }

    protected static function matchLiteralAttribute($attr, &$m) {
        return !!preg_match('/^([^\t\r\n\f \/>"\'=]+)="([^"]+)"/', $attr, $m);
    }

    protected static function matchBooleanAttribute($attr, &$m) {
        return !!preg_match('/^([^\t\r\n\f \/>"\'=]+)=(true|false)/', $attr, $m);
    }

    protected static function matchVariableAttribute($attr, &$m) {
        return !!preg_match('/^([^\t\r\n\f \/>"\'=]+)=(.+)/', $attr, $m);
    }

    protected static function getAttribute($attr, Scope $scope) {
        $m = [];

        if (static::matchLiteralAttribute($attr, $m))
            return [$m[1] => $m[2]];

        if (static::matchBooleanAttribute($attr, $m))
            return [$m[1] => $m[2] === 'true' ? true : false];

        if (static::matchVariableAttribute($attr, $m))
            return [$m[1] => $scope->get($m[2])];
    }

}