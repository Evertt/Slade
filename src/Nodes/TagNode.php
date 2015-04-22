<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;
use Slade\TemplateBlock;

/**
 * @node /^[a-z.#]/
 */
class TagNode extends Node
{
    public static $selfClosingTags = [
        'area','base','br','col','command','embed','hr','img',
        'input','keygen','link','meta','param','source','track','wbr',
    ];

    protected static $defaultTagName = 'div';

    public static function parse(TemplateBlock $block, Scope $scope, Scope $sections)
    {
        $line = $block->getLine();

        if (substr($line, 0, 7) == 'doctype') {
            $block->setLine(static::parseDoctype(substr($line, 0, 8)));

            return $block;
        }

        list($tag, $rest) = preg_split('/^\S+\K\s+|$/', $line);
        list($tagName, $id, $class) = static::split($tag);
        list($attributes, $content) = static::splitAttrContent($rest);
        $attributes = "$id $class $attributes";

        $attributes = static::getAttributes($attributes, $scope)['string'];
        $attributes = $attributes ? " $attributes" : '';

        $attributes = static::combineClasses($attributes);

        if (static::isSelfClosingTag($tagName)) {
            $block->setLine("<$tagName$attributes>");
            $block->setInsides('');

            return $block;
        }

        if (substr($content, 0, 1) == '=') {
            $content = VariableNode::parse(new TemplateBlock($content), $scope);
        }
        else
        {
            $content = static::replaceVars($content, $scope, $sections);
        }

        $block->setLine($content);
        $block->parseInsides($scope, $sections);
        $block->indentInsides();
        $block->wrap("<$tagName$attributes>", "</$tagName>");

        return $block;
    }

    protected static function split($tag)
    {
        preg_match('/^([\w-]+)?(?:#([\w-]+))?(?:\.([\w-.]+))?$/', $tag, $matches);

        list($match, $tagName, $id, $class) = $matches + array_fill(0, 4, '');

        if (!$tagName) {
            $tagName = static::$defaultTagName;
        }

        $class = str_replace('.', ' ', $class);

        $id = $id ? "id=\"$id\"" : '';
        $class = $class ? "class=\"$class\"" : '';

        return [$tagName, $id, $class];
    }

    protected static function splitAttrContent($str) {
        if (preg_match('/(([^\s\/>"\'=]+)=("[^"]+"|\S+)\s*)+/', $str, $attr)) {
            $attributes = trim($attr[0]);
            $content = str_replace($attr[0], '', $str);
            return [$attributes, $content];
        }

        return ['', $str];
    }

    protected static function combineClasses($attributes)
    {
        $classes = [];
        preg_match_all('/class="([^"]+)"/', $attributes, $matches);

        foreach ($matches[1] as $class) {
            $classes = array_merge($classes, explode(' ', trim($class)));
        }

        $classes = implode(' ', $classes);

        $attributes = preg_replace(
            '/class="[^"]+"/',
            'class="'.$classes.'"',
            $attributes,
            1
        );

        $attributes = preg_replace(
            '/ class="(?!'.$classes.')[^"]*"/',
            '',
            $attributes
        );

        return $attributes;
    }

    protected static function parseDoctype($doc)
    {
        if ($doc == 'html' || $doc = 'html5') {
            return '<!DOCTYPE html>';
        }

        if ($doc == '1.1') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" '
                    + '"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
        }

        if ($doc == 'strict') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" '
                    + '"http://www.w3.org/TR/html4/strict.dtd">';
        }

        if ($doc == 'frameset') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" '
                    + '"http://www.w3.org/TR/html4/frameset.dtd">';
        }

        if ($doc == 'transitional') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" '
                    + '"http://www.w3.org/TR/html4/loose.dtd">';
        }

        if ($doc == 'mobile') {
            return '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" '
                    + '"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">';
        }

        if ($doc == 'basic') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" '
                    + '"http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">';
        }
    }

    protected static function isSelfClosingTag($tag)
    {
        return array_search($tag, static::$selfClosingTags) !== false;
    }

}
