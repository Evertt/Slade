<?php namespace Slade;

class Template
{
    static function compile($template)
    {
        $template = static::normalizeNewlines($template);
        $tree     = static::lex($template);
        $html     = static::parse($tree);

        if (app('config')->get('app.debug'))
        {
            $html = static::tidy($html);
        }

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

    static function tidy($html)
    {
        $needles = [
            '~<style>*?>|(<script.*?>(?!\s*</script>))~',
            '~</style>|((<script>\s*\K)</script>)~'
        ];

        $replacements = ['$0<![CDATA[', ']]>$0'];

        $html = preg_replace($needles, $replacements, $html);

        $settings = [
            'indent' => true,
            'input-xml' => true,
            'escape-cdata' => true
        ];

        //$tidy = tidy_parse_string($html, $settings, 'utf8');

        return $html;
        
        return preg_replace('~(</.+>|<.+/>|-->)(?=\n *<\w+)~m', "\$1\n", $tidy);
    }
}