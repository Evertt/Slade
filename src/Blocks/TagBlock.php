<?php namespace Slade\Blocks;

use Slade\Block;
use Slade\Template;

/**
 * @token /^(?!css|js|doctype)[a-z#.][\w-]*\b/
 */
class TagBlock
{
    const DEFAULT_TAG_NAME = 'div';

    protected static $tokens = [
        'tagName'           => '/^[a-z][\w-]*\b/',
        'identifier'        => '/^([#.])([\w-]+)\b/',
        'attribute'         => '/^([^\s\/>"\'=]+)=("[^"\r\n\f\v]+"|\'[^\'\r\n\f\v]+\'|\S+)/',
        'variableContent'   => '/^(==|=) *.+/',
        'nodeContent'       => '/^: *([\s\S]*?)(?=\n*$)/D',
        'yieldContent'      => '/^-\s*(\S*)/',
        'textContent'       => '/^\|.+/'
    ];

    protected static $selfClosingTags = [
        'area','base','br','col','command','embed','hr','img',
        'input','keygen','link','meta','param','source','track','wbr',
    ];

    static function lex($block)
    {
        $tagName     = static::getTagName($block) ?: static::DEFAULT_TAG_NAME;
        $identifiers = static::getIdentifiers($block);
        $attributes  = static::getAttributes($block, $identifiers);
        $content     = static::getContent($block);
        $children    = Template::lex($block);

        return compact(
                'tagName',
                'attributes',
                'content',
                'children'
            );
    }

    static function parse($tree)
    {
        extract($tree);

        $attributes = static::setAttributes($attributes);

        $content = static::setContent($content);

        $children = Template::parse($children);

        if (static::isSelfClosingTag($tagName))
        {
            return "<$tagName$attributes />";
        }

        return "<$tagName$attributes>$content$children</$tagName>";;
    }

    protected static function setAttributes($attributes)
    {
        $html = '';

        foreach($attributes as $name => $value)
        {
            $html .= static::setAttribute($name, $value);
        }

        return $html;
    }

    protected static function setAttribute($name, $value)
    {
        if ($value === 'true')
        {
            return " $name";
        }

        if (starts_with($value, '='))
        {
            $value = substr($value, 1);

            return " $name=\"<?= $value ?>\"";
        }

        if ($value !== 'false')
        {
            return " $name=\"<?= e($value) ?>\"";
        }
    }

    protected static function setContent($content)
    {
        return is_array($content) ? Block::parse($content) : $content;
    }

    protected static function getTagName(&$block)
    {
        extract(static::$tokens);

        $token = match($tagName, $block);
        
        return $token ? $token[0] : null;
    }

    protected static function getIdentifiers(&$block)
    {
        extract(static::$tokens);
        $id = ''; $classes = [];

        while ($token = match($identifier, $block))
        {
            if ($token[1] == '#')
            {
                $id = $token[2];
            }

            if ($token[1] == '.')
            {
                $classes[] = $token[2];
            }
        }

        return compact('id', 'classes');
    }

    protected static function getAttributes(&$block, $identifiers)
    {
        extract($identifiers);
        $attributes = [];

        if ($id)
        {
            $attributes['id'] = surround($id, '"');
        }

        if ($classes)
        {
            $classes = implode(' ', $classes);
            $attributes['class'] = surround($classes, '"');
        }

        extract(static::$tokens);

        while ($token = match($attribute, $block))
        {
            $attributes[$token[1]] = $token[2];
        }

        return $attributes;
    }

    protected static function getContent(&$block)
    {
        extract(static::$tokens);

        if ($token = match($variableContent, $block))
        {
            return Block::lex($token[0]);
        }

        if ($token = match($nodeContent, $block))
        {
            return Block::lex($token[1]);
        }

        if ($token = match($yieldContent, $block))
        {
            return Block::lex($token[0]);
        }

        if ($token = match($textContent, $block))
        {
            return Block::lex("|$token[0]");
        }
    }

    protected static function isSelfClosingTag($tagName)
    {
        return array_search($tagName, static::$selfClosingTags) !== false;
    }
}