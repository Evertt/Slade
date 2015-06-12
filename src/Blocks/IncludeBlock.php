<?php namespace Slade\Blocks;

use Slade\Block;
use Slade\Template;

/**
 * @token /^\+/
 */
class IncludeBlock
{
    protected static $tokens = [
        'view'            => '/^\+\s*([\'"$]?\w([\w.]*\w[\'"]?))/',
        'attribute'       => '/^([^\s\/>"\'=]+)=("[^"\r\n\f\v]+"|\'[^\'\r\n\f\v]+\'|\S+)/',
        'textContent'     => '/^(?!=|:).+/',
        'variableContent' => '/^(==|=) *.+/',
        'nodeContent'     => '/^: *([\s\S]*?)(?=\n*$)/D'
    ];

    static function makeTree($block)
    {
        $view        = static::getView($block);
        $attributes  = static::getAttributes($block);
        $content     = static::getContent($block);

        $newLines    = count_new_lines($block);
        $block       = trim($block, "\r\n");
        $indentation = measure_indentation($block);
        $block       = outdent($block, $indentation);

        $children    = Template::makeTree($block);

        return compact(
            'view',
            'attributes',
            'content',
            'children',
            'indentation',
            'newLines'
        );
    }

    protected static function getView(&$block)
    {
        extract(static::$tokens);

        $token = match($view, $block);
        
        return $token ? $token[1] : null;
    }

    protected static function getAttributes(&$block)
    {
        extract(static::$tokens);
        $attributes = [];

        while ($token = match($attribute, $block))
        {
            $attributes[$token[1]] = $token[2];
        }

        return $attributes;
    }

    protected static function getContent(&$block)
    {
        extract(static::$tokens);

        if ($token = match($textContent, $block))
        {
            return Block::makeTree("|$token[0]");
        }

        if ($token = match($variableContent, $block))
        {
            return Block::makeTree($token[0]);
        }

        if ($token = match($nodeContent, $block))
        {
            return Block::makeTree($token[1]);
        }
    }


    static function parseTree($tree)
    {
        extract($tree);

        $view = addcslashes($view, '"');

        $attributes = static::setAttributes($attributes);

        $content = static::setContent($content);

        $children = Template::parseTree($children);

        if ($children)
        {
            $children = indent($children, $indentation) . "\n";
        }

        $result = "<?php \$__env->startInclude(); ?>$content"
                . repeat("\n", $newLines[0])
                . $children
                . "<?php \$__env->endInclude($view, array_merge(\$__data, $attributes)); ?>"
                . repeat("\n", $newLines[1]);

        return $result;
    }

    protected static function setAttributes($attributes)
    {
        $arr = [];

        foreach($attributes as $name => $value)
        {
            $arr[] = "'$name' => $value";
        }

        return '[' . implode(', ', $arr) . ']';
    }

    protected static function setContent($content)
    {
        return is_array($content) ? Block::parseBlock($content) : $content;
    }
}
