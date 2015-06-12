<?php namespace Slade\Blocks;

use Slade\Template;

/**
 * @token /^_/
 */
class ExtendBlock
{
    protected static $tokens = [
        'view'        => '/^_\s*([\'"$]?\w([\w.]*\w[\'"]?))/',
        'attribute'   => '/^([^\s\/>"\'=]+)=("[^"\r\n\f\v]+"|\'[^\'\r\n\f\v]+\'|\S+)/'
    ];

    static function makeTree($block)
    {
        $view        = static::getView($block);
        $attributes  = static::getAttributes($block);

        $newLines    = count_new_lines($block);
        $block       = trim($block, "\r\n");
        $indentation = measure_indentation($block);
        $block       = outdent($block, $indentation);

        $children    = Template::makeTree($block);

        return compact(
            'view',
            'attributes',
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

    static function parseTree($tree)
    {
        extract($tree);

        $view = addcslashes($view, '"');

        $attributes = static::setAttributes($attributes);

        $children = Template::parseTree($children);

        if ($children)
        {
            $children = indent($children, $indentation) . "\n";
        }

        $result = "<?php \$__env->startExtension(); ?>"
                . repeat("\n", $newLines[0])
                . $children
                . "<?php \$__env->endExtension($view, array_merge(\$__data, $attributes)); ?>"
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
}
