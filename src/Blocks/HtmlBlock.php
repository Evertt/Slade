<?php namespace Slade\Blocks;

/**
 * @token /^</
 */
class HtmlBlock
{
    static function lex($html)
    {
        return compact('html');
    }

    static function parse($tree)
    {
        extract($tree);

        if (starts_with($html, '<?')) return $html;
        
        return replaceFunc('<?= "%s" ?>', $html);
    }
}
