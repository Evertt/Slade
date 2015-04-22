<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;
use Slade\TemplateBlock;

/**
 * @node /^@/
 */
class SectionNode extends Node
{
    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $line = $block->stripLine();

        list($section, $content) = array_pad(explode(' ', $line, 2), 2, '');

        if (starts_with($content, '='))
        {
            $result = VariableNode::parse(new TemplateBlock($content), $scope);
        }

        elseif ($content)
        {
            $result = e($content);
        }

        else
        {
            $result = $block->parseInsides($scope, $sections);
        }

        $sections[$section] = $result;
    }
}
