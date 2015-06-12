<?php namespace Slade\Blocks;

/**
 * @token /^(js:|css:)/
 */
class CodeBlock
{
    protected static $tokens = [
        'func' => '/(?<!\$|\\\){(?!\s|\$|\\\)(.+?)(?<!\s)}/',
    ];

    static function makeTree($block)
    {
        $lang     = strtok($block, ':');
        $block    = substr($block, strlen($lang) + 1);
        $newLines = count_new_lines($block);
        $code     = trim($block, "\n");

        return compact('lang', 'code', 'newLines');
    }

    static function parseTree($tree)
    {
        extract($tree);

        $code = addcslashes($code, '"');

        $code = '<?= "' . static::replaceFunc($code) . '" ?>';

        $lang = $lang == 'js' ? 'script' : 'style';

        $code = "<$lang>" . repeat("\n", $newLines[0])
              . $code . "\n</$lang>"
              . repeat("\n", $newLines[1]);

        return $code;
    }

    static function replaceFunc($html)
    {
        extract(static::$tokens);

        return preg_replace($func, '{$_fn($1)}', $html);
    }
}
