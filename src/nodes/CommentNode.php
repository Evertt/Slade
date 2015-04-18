<?php namespace Slade\Nodes;

use Slade\Scope;

/**
 * @node /^\//
 */
class CommentNode extends Node
{
    public static function parse($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        $newLines = countNewLines($node.$inner);

        $node = static::strip($node);

        if (starts_with($node, '!'))
        {
            $node = static::strip($node);

            if ($inner)
            {
                $inner = surround($inner, PHP_EOL);
                $inner = indent($inner, $depth);
            }

            return "<!-- $node $inner-->" . repeat(PHP_EOL, $newLines);
        }
    }
}