<?php

namespace Slade\nodes;

use Slade\Scope;

/**
 * @node /^javascript:|css:/
 */
class CodeNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $newLines = countNewLines($node.$inner);

        $inner = surround($inner, PHP_EOL);

        $inner = indent($inner, $depth);

        if (starts_with($node, 'javascript'))
        {
            return "<script>$inner</script>" . repeat(PHP_EOL, $newLines);
        }

        if (starts_with($node, 'css'))
        {
            return "<style>$inner</style>" . repeat(PHP_EOL, $newLines);
        }
    }
}
