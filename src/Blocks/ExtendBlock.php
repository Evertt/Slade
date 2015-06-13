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

    static function lex($block)
    {
        $view       = static::getView($block);
        $attributes = static::getAttributes($block);
        $children   = Template::lex($block);

        return compact(
            'view',
            'attributes',
            'children'
        );
    }

    static function parse($tree)
    {
        extract($tree);

        $view       = addcslashes($view, '"');
        $attributes = static::setAttributes($attributes);
        $children   = Template::parse($children);

        $output  = $children;
        $output .= "<?php extract($attributes); ?>";
        $output .= '<?php $__d = array_except(get_defined_vars(), array("__data", "__path")) ?>';
        $output .= "<?= \$__env->make(\"$view\", \$__d)->render(); ?>";
        $output .= '<?php unset($__d) ?>';

        return $output;
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
