<?php

namespace Slade\nodes;

use Slade\Slade;
use Slade\Scope;
use Slade\Parser;
use Slade\TemplateBlock;

/**
 * @node /^_/
 */
class ExtendNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $fileName = static::strip($block->getLine());

        $file = Slade::retrieveFile($fileName);

        $insides = $block->getInsides();

        $extendedTemplate = $insides . PHP_EOL . $file;

        return Parser::parse($extendedTemplate, $scope, $sections);
    }
}
