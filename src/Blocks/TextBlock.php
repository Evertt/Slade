<?php namespace Slade\Blocks;

/**
 * @token /^\|/
 */
class TextBlock
{
    protected static $tokens = [
        'func' => '/(?<!\$|\\\){(?!\s|\$|\\\)(.+?)(?<!\s)}/',
    ];

    static function makeTree($block)
    {
        $newLines = count_new_lines($block);
        $text     = static::getText($block);

        return compact('text', 'newLines');
    }

    protected static function getText($block)
    {
        $block = ltrim($block, '| ');

        return rtrim($block, "\n");
    }

    static function parseTree($tree)
    {
        extract($tree);

        $text = addcslashes($text, '"');

        $php  = '<?= e("' . static::replaceFunc($text) . '") ?>';

        return $php . repeat("\n", $newLines[1]);
    }

    static function replaceFunc($text)
    {
        extract(static::$tokens);

        return preg_replace($func, '{$_fn($1)}', $text);
    }
}