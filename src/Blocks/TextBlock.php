<?php namespace Slade\Blocks;

/**
 * @token /^\|/
 */
class TextBlock
{
    static function lex($block)
    {
        $text = ltrim($block, '| ');

        return compact('text');
    }

    static function parse($tree)
    {
        extract($tree);

        $text = addcslashes($text, '"');

        return replaceFunc('<?= e("%s") ?>', $text);
    }
}