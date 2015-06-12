<?php namespace Slade\Blocks;

/**
 * @token /^</
 */
class HtmlBlock
{
    protected static $tokens = [
        'func' => '/(?<!\$|\\\){(?!\s|\$|\\\)(.+?)(?<!\s)}/',
    ];

    static function makeTree($block)
    {
        $newLines = count_new_lines($block);
        $html     = trim($block, "\r\n");

        return compact('html', 'newLines');
    }

    static function parseTree($tree)
    {
        extract($tree);

        if (starts_with($html, '<?'))
        {
            return $html . repeat("\n", $newLines[1]);
        }

        $html = addcslashes($html, '"');

        $php  = '<?= "' . static::replaceFunc($html) . '" ?>';

        return $php . repeat("\n", $newLines[1]);
    }

    static function replaceFunc($html)
    {
        extract(static::$tokens);

        return preg_replace($func, '{$_fn($1)}', $html);
    }
}
