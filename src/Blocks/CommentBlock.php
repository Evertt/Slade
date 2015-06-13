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
            $block    = substr($block, 2);
            $line     = static::getFirstLine($block);
            $block    = trim($block, "\n") . ' ';
            $tree     = compact('line', 'block');
        }

        return $tree;
    }

    static function parse($tree)
    {
        if (!extract($tree)) return;

        return "<!--$line$block-->";
    }

    protected static function getFirstLine(&$block)
    {
        if ($token = match('/^[\s\S]*?\n/', $block))
        {
            return $token[0];
        }
    }
}