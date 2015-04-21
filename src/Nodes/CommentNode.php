<?php namespace Slade\Nodes;

use Slade\TemplateBlock;

/**
 * @node /^\//
 */
class CommentNode extends Node
{
    public static function parse(TemplateBlock $block)
    {
        $line = $block->stripLine();

        if (starts_with($line, '!'))
        {
            $block->stripLine();
            $block->indentInsides();

            $block->wrap('<!-- ', ' -->');

            return $block;
        }
    }
}