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
        $line = $block->getLine();

        $varName = static::strip($line);
        $var = $scope[$varName];

        if (starts_with($line, '==')) {
            $block->setLine($var);

            return $block;
        }

        $block->setLine(e($var));

        return $block;
    }
}
