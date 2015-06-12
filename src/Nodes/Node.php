<?php

namespace Slade\nodes;

use Slade\Scope;

abstract class Node
{
    protected static function strip($node)
    {
        return trim($node, $node[0]." \r\n");
    }

    protected static function stripOperator($node)
    {
        return ltrim($node, $node[0].' ');
    }

    protected static function replaceVars($node, Scope $scope, Scope $sections)
    {
        preg_match_all('/(?<!\\\\){{\s*(\w+)\s*}}/', $node, $escapedVars);

        foreach ($escapedVars[1] as $i => $var) {
            $node = str_replace($escapedVars[0][$i], e($scope[$var]), $node);
        }

        preg_match_all('/(?<!\\\\){!\s*(\w+)\s*!}/', $node, $unescapedVars);

        foreach ($unescapedVars[1] as $i => $var) {
            $node = str_replace($unescapedVars[0][$i], $scope[$var], $node);
        }

        preg_match_all('/(?<!\\\\){-\s*(\w+)\s*-}/', $node, $yieldedSections);

        foreach ($yieldedSections[1] as $i => $var) {
            $node = str_replace($yieldedSections[0][$i], $sections[$var], $node);
        }

        $node = preg_replace('/\\\\({{\s*(\w+)\s*}})/', '$1', $node);
        $node = preg_replace('/\\\\({!\s*(\w+)\s*!})/', '$1', $node);
        $node = preg_replace('/\\\\({-\s*(\w+)\s*-})/', '$1', $node);

        return $node;
    }

    protected static function getAttributes($string, Scope $scope) {
        $array = [];
        $pattern = '/([^\s\/>"\'=]+)=("[^"]"|\'[^\']\'|[\w.]+)/';

        $callback = function ($attr) use (&$array, $scope) {
            if ($attr[2] === 'true') {
                $array[$attr[1]] = true;
                return $attr[1];
            }

            if ($attr[2] === 'false') {
                $array[$attr[1]] = false;
                return '';
            }

            if (starts_with($attr[2], ['"', "'"])) {
                $array[$attr[1]] = substr($attr[2], 1, -1);
                return $attr[0];
            }

            $array[$attr[1]] = $scope[$attr[2]];
            $value = $array[$attr[1]] ?: false;

            if ($value === true) {
                return $attr[1];
            }

            if ($value === false) {
                return '';
            }

            if (is_array($value)) {
                $value = json_encode($value);
            }

            return $attr[1] . '="' . e($value) . '"';
        };

        $string = trim(preg_replace_callback($pattern, $callback, $string));

        return compact('string','array');
    }
}
