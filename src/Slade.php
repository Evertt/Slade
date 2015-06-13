<?php namespace Slade;

class Slade
{
    static $production = false;

    static function make($filename, $data = [])
    {
        $html = Parser::make($filename, $data);

        if (!static::$production)
        {
            $html = static::tidy($html);
        }

        return $html;
    }

    static function tidy($html)
    {
        $html = preg_replace(['~<style.*?>|<script.*?>~', '~</style>|</script>~'], ['$0<![CDATA[', ']]>$0'], $html);
        $tidy = tidy_parse_string($html, ['indent'=>1,'input-xml'=>1,'escape-cdata'=>1], 'utf8');
        return  preg_replace('~(</\w+>|<.+/>|-->)(?=\n *<\w+)~m', "\$1\n", $tidy);
    }
}