<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^javascript:|css:/
 */
class CodeNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope)
    {
        $line = $block->getLine();
        $insides = $block->getInsides();

        $code = static::extractCode($line);
        $language = static::extractLanguage($line);

        $block->setLine(static::replaceVars($code, $scope));

        $block->setInsides(static::replaceVars($insides, $scope));

        $block->indentInsides();

        if ($language === 'javascript')
        {
            $block->wrap('<script>', '</script>');
        }

        if ($language === 'css')
        {
            $block->wrap('<style>', '</style>');
        }

        return $block;
    }

    protected static function extractLanguage($line)
    {
        preg_match('/^(\w+):/', $line, $match);

        return isset($match[1]) ? $match[1] : null;
    }

    protected static function extractCode($line)
    {
        preg_match('/: (.*)$/', $line, $match);

        return isset($match[1]) ? $match[1] : null;
    }
}
