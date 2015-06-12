<?php namespace Slade;

class Template
{
    static function parseTemplate($template)
    {
        $template = static::normalizeNewlines($template);
        $tree     = static::makeTree($template);
        $html     = static::parseTree($tree);

        return $html;
    }

    static function normalizeNewlines($template)
    {
        return preg_replace('/\r\n?|\n\r?/', "\n", $template);
    }

    static function makeTree($template)
    {
        $blocks = [];
        $regex = '/\S[\s\S]*?(\n+(?=\S)|$)/D';
        preg_match_all($regex, $template, $matches);

        foreach($matches[0] as $match)
        {
            $blocks[] = Block::makeTree($match);
        }

        return $blocks;
    }

    static function parseTree($tree)
    {
        $html = '';

        foreach($tree as $block)
        {
            $html .= Block::parseBlock($block);
        }

        return $html;
    }
}