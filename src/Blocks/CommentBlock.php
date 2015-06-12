<?php namespace Slade\Blocks;

/**
 * @token /^\//
 */
class CommentBlock
{
    static function makeTree($block)
    {
        $tree = [];

        if (starts_with($block, '/!'))
        {
            $block    = substr($block, 2);
            $line     = static::getFirstLine($block);
            $newLines = count_new_lines($block);
            $block    = trim($block, "\n");
            $tree     = compact('line', 'block', 'newLines');
        }

        return $tree;
    }

    static function getFirstLine(&$block)
    {
        if ($token = match('/^.*/', $block))
        {
            return $token[0];
        }
    }

    static function parseTree($tree)
    {
        if (!extract($tree))
        {
            return;
        }

        $block .= $block ? "\n" : ' ';

        $comment = '<!--' . $line
                 . repeat("\n", $newLines[0])
                 . $block . '-->'
                 . repeat("\n", $newLines[1]);

        return $comment;
    }
}