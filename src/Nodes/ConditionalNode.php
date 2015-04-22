<?php

namespace Slade\nodes;

use Slade\Parser;
use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^\?|^!/
 */
class ConditionalNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $positiveOrNegative = $block->getLine()[0];

        $conditional = preg_replace(
            '/\b(?<![\'"])([^\W\d][\w.]*)\b(?![\'"])/',
            '$scope[\'$1\']',
            $block->stripLine()
        );

        if ($positiveOrNegative == '?')
        {
            $conditional = 'return (boolean) (' . $conditional . ');';
        }

        if ($positiveOrNegative == '!')
        {
            $conditional = 'return (boolean) ( ! (' . $conditional . ') );';
        }

        if (eval($conditional))
        {
            $block->removeLine();

            $block->parseInsides($scope, $sections);

            return $block;
        }
    }
}
