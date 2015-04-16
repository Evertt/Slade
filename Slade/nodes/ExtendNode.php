<?php

namespace Slade\nodes;

use Slade\Slade;
use Slade\Scope;
use Slade\Parser;

/**
 * @node /^_/
 */
class ExtendNode extends Node
{
    public static function parse($node, $inner, Scope & $scope, Scope & $sections)
    {
        $file = static::getFilePath($node);

        $depth = Parser::getDepth(strtok($inner, PHP_EOL));

        $infix = static::format($inner, $depth);

        $template = static::merge($infix, $file);

        return Parser::parse($template, $scope, $sections);
    }

    protected static function getFilePath($node)
    {
        $path = str_replace('.', '/', static::stripOperator($node));

        return 'templates/'.$path.'.slade';
    }

    protected static function format($inner, $depth)
    {
        $lines = explode(PHP_EOL, $inner);

        $removeIndentation = function (&$line) use ($depth) {
            $line = substr($line, $depth);
        };

        array_walk($lines, $removeIndentation);

        return $lines;
    }

    protected static function merge($inner, $file)
    {
        return array_merge($inner, Slade::retrieveFile($file));
    }
}
