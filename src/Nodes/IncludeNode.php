<?php

namespace Slade\nodes;

use Slade\Slade;
use Slade\Scope;
use Slade\TemplateBlock;

/**
 * @node /^\+/
 */
class IncludeNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope)
    {
        $line = $block->getLine();

        $line = static::strip($line);

        $file = strtok($line, " \r\n");

        $data = static::getAttributes($line, $scope)['array'];

        $newScope = new Scope($data, $scope);

        $parsed = Slade::parse($file, $newScope);

        $block->removeLine();

        $block->setInsides($parsed);

        return $block;;
    }
}
