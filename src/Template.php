<?php namespace Slade;

class Template
{
    static function compile($template)
    {
        $template = static::normalizeNewlines($template);
        $tree     = static::lex($template);
        $html     = static::parse($tree);

        return $html;
    }

    static function normalizeNewlines($template)
    {
        return preg_replace('/\r\n?|\n\r?/', "\n", $template);
    }

    static function lex($template)
    {
        $blocks = [];
        $regex = '/\S[\s\S]*?(\n+(?=\S)|$)/D';
        preg_match_all($regex, dedent($template), $matches);

        foreach($matches[0] as $match)
        {
            $blocks[] = Block::lex($match);
        }

        return $blocks;
    }

    static function parse($tree)
    {
        $html = '';

        foreach($tree as $block)
        {
            $html .= Block::parse($block);
        }

        return $html;
    }
}