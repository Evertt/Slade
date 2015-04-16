<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^javascript:|css:/
 */
class CodeNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        if ($node == 'javascript:') {
            return '<script>'.PHP_EOL.
                        $inner.PHP_EOL.
                    '</script>'.PHP_EOL;
        }

        if ($node == 'css:') {
            return '<style>'.PHP_EOL.
                        $inner.PHP_EOL.
                    '</style>'.PHP_EOL;
        }
    }
}
