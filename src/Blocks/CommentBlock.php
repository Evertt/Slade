<?php namespace Slade\Blocks;

/**
 * @token /^\//
 */
class CommentBlock
{
    static function lex($block)
    {
        $tree = [];

        if (starts_with($block, '/!'))
        {
            $block   = substr($block, 2);
            $comment = trim($block);
            $tree    = compact('comment');
        }

        return $tree;
    }

    static function parse($tree)
    {
        if (!extract($tree)) return;

        return "<!-- $comment -->";
    }
}