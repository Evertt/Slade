<?php

namespace Slade;

class Parser
{
    protected static $nodes = [];

    public static function initNodes()
    {
        foreach (glob(__DIR__.'/nodes/*?Node.php') as $filename) {
            $class = 'Slade\Nodes\\'.basename($filename, '.php');

            $rc = new \ReflectionClass($class);

            preg_match('/@node (.+)/i', $rc->getDocComment(), $m);

            static::$nodes[$m[1]] = $class;
        }
    }

    public static function parse($template = '', Scope $scope = null, Scope $sections = null)
    {
        if (!static::$nodes) {
            static::initNodes();
        }

        if (!$scope) {
            $scope = new Scope();
        }

        if (!$sections) {
            $sections = new Scope();
        }

        $blocks = static::getBlocks($template);

        $html = static::parseBlocks($blocks, $scope, $sections);

        return $html;
    }

    public static function getBlocks($template = '')
    {
        $blocks = [];
        $regex = '/\S[\s\S]*?((\r\n?|\n\r?)+(?=\S)|$)/D';
        preg_match_all($regex, $template, $matches);

        foreach($matches[0] as $match)
        {
            $blocks[] = new TemplateBlock($match);
        }

        return $blocks;
    }

    protected static function parseBlocks($blocks, Scope $scope, Scope $sections)
    {
        $html = '';

        foreach ($blocks as $block)
        {
            $html .= static::parseBlock($block, $scope, $sections);
        }

        return $html;
    }

    protected static function parseBlock($block, Scope $scope, Scope $sections)
    {
        foreach (static::$nodes as $pattern => $class)
        {
            if (preg_match($pattern, $block->getLine()))
            {
                return $class::parse($block, $scope, $sections);
            }
        }

        return $block;
    }
}
