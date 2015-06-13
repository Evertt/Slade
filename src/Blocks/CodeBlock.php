<?php namespace Slade\Blocks;

/**
 * @token /^(js:|css:)/
 */
class CodeBlock
{
    static function lex($block)
    {
        $lang  = strtok($block, ':');
        $block = substr($block, strlen($lang) + 1);
        $code  = trim($block, "\n");

        return compact('lang', 'code');
    }

    static function parse($tree)
    {
        extract($tree);

        $code = addcslashes($code, '"');
        $code = replaceFunc('<?= "%s" ?>', $code);
        $lang = $lang == 'js' ? 'script' : 'style';

        return "<$lang>\n$code\n\n</$lang>";
    }
}
