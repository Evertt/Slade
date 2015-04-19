<?php namespace Slade\Nodes;

/**
 * @node /^\//
 */
class CommentNode extends Node
{
    public static function parse($node, $inner, $depth)
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