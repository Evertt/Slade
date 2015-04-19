<?php

namespace Slade\nodes;

/**
 * @node /^javascript:|css:/
 */
class CodeNode extends Node
{
    public static function parse($node, $inner, $depth)
    {
        $newLines = countNewLines($node.$inner);

        $codeBegins = strpos($node, ':') + 2;

        $code = trim(substr($node, $codeBegins));

        if ($inner)
        {
            $inner = surround($inner, PHP_EOL);

            $inner = indent($inner, $depth);
        }

        if (starts_with($node, 'javascript'))
        {
            return "<script>$code$inner</script>" . repeat(PHP_EOL, $newLines);
        }

        if (starts_with($node, 'css'))
        {
            return "<style>$code$inner</style>" . repeat(PHP_EOL, $newLines);
        }
    }
}
