<?php

namespace Slade\nodes;

use Slade\Scope;
use Slade\Parser;

/**
 * @node /^[a-z.#]/
 */
class TagNode extends Node
{
    const self_closing_tags = [
        'area','base','br','col','command','embed','hr','img',
        'input','keygen','link','meta','param','source','track','wbr',
    ];

    protected static $defaultTagName = 'div';

    public static function parse($node, $inner, $depth, Scope $scope, Scope $sections)
    {
        $newLines = countNewLines($node.$inner);

        if (substr($node, 0, 7) == 'doctype') {
            return static::parseDoctype(substr($node, 0, 8)) . str_repeat(PHP_EOL, $newLines);
        }

        list($tag, $rest) = preg_split('/^\S+\K\s+|$/', $node);
        list($tagName, $id, $class) = static::split($tag);
        list($attributes, $content) = static::splitAttrContent($rest);
        $attributes = "$id $class $attributes";

        $attributes = static::getAttributes($attributes, $scope)['string'];
        $attributes = $attributes ? " $attributes" : '';

        $infix = Parser::parse($inner, $scope, $sections);

        if ($infix) {
            $infix = indent($infix, 2);
            $infix = finish(rtrim($infix), PHP_EOL);
            $content = finish($content, str_repeat(PHP_EOL, countNewLines($node)));
        } else {
            $content = rtrim($content);
        }

        $attributes = static::combineClasses($attributes);

        if (static::isSelfClosingTag($tagName)) {
            return "<$tagName$attributes>" . str_repeat(PHP_EOL, $newLines);
        }

        if (substr($content, 0, 1) == '=') {
            $content = trim(VariableNode::parse($content, null, $depth, $scope));
        }
        else
        {
            $content = static::replaceVars($content, $scope);
        }

        return "<$tagName$attributes>$content$infix</$tagName>" . str_repeat(PHP_EOL, $newLines);
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
        return array_search($tag, static::self_closing_tags) !== false;
    }

    protected static function formatAttributeSpaces($attributes)
    {
        $attributes = trim(str_replace('  ', ' ', $attributes));

        return $attributes ? " $attributes" : '';
    }

}
