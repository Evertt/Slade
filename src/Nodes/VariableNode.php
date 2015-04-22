<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^=/
 */
class VariableNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope)
    {
        $sign = $block->getLine()[1];

        $statement = preg_replace(
            '/\b(?<![\'"$])([^\W\d][\w.]*)\b(?![\'"(:-])/',
            '$scope[\'$1\']',
            $block->stripLine()
        );

        $var = eval('return ' . $statement . ';');

        if ($sign == '=') {
            $block->setLine($var);

            return $block;
        }

        $block->setLine(e($var));

        return $block;
    }
}
