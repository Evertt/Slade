<?php

namespace Slade\nodes;

use Slade\Scope;

abstract class Node
{
    protected static $attribute = '([^\t\r\n\f \/>"\'=]+)';
    protected static $literal   = '"([^"]+)"';
    protected static $boolean   = '(true|false)';
    protected static $variable  = '(.+)';

    protected static function stripOperator($node)
    {
        return ltrim($node, $node[0].' ');
    }

    protected static function matchAttribute($attr, $value, &$m)
    {
        $attribute = static::$attribute;

        return !!preg_match("/^$attribute=$value$/", $attr, $m);
    }

    protected static function replaceVars(&$node, Scope $scope)
    {
        preg_match_all('/{{\s*(\w+)\s*}}/', $node, $escapedVars);

        foreach ($escapedVars[1] as $i => $var) {
            $node = str_replace($escapedVars[0][$i], he($scope->get($var)), $node);
        }

        preg_match_all('/{!\s*(\w+)\s*!}/', $node, $unescapedVars);

        foreach ($unescapedVars[1] as $i => $var) {
            $node = str_replace($unescapedVars[0][$i], $scope->get($var), $node);
        }
    }
}
