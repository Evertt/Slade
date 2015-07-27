<?php namespace Slade\Blocks;

use Slade\Block;
use Slade\Template;

/**
 * @token /^\+/
 */
class IncludeBlock
{
    protected static $tokens = [
        'view'            => '/^\+\s*([\'"$]?[\w-]([\w.-]*[\w-][\'"]?))/',
        'attribute'       => '/^([^\s\/>"\'=]+)=("[^"\r\n\f\v]+"|\'[^\'\r\n\f\v]+\'|\S+)/',
        'textContent'     => '/^(?!=|:).+/',
        'variableContent' => '/^(==|=) *.+/',
        'nodeContent'     => '/^: *([\s\S]*?)(?=\n*$)/D'
    ];

    static function lex($block)
    {
        $view       = static::getView($block);
        $attributes = static::getAttributes($block);
        $content    = static::getContent($block);
        $children   = Template::lex($block);

        return compact('view','attributes','content','children');
    }

    static function parse($tree)
    {
        extract($tree);

        $view       = addcslashes($view, '"');
        $attributes = static::setAttributes($attributes);
        $content    = static::setContent($content);
        $children   = Template::parse($children);

        $output  = "<?php ob_start() ?>$content$children";
        $output .= '<?php $__tmp = ob_get_clean(); ?>';
        $output .= "<?php extract($attributes); ?>";
        $output .= '<?php $__d = array_except(get_defined_vars(), array("__data", "__path")) ?>';
        $output .= '<?= $__env->make("'.$view.'", $__d)->render() ?: $__tmp ?>';
        $output .= '<?php unset($__tmp, $__d); ?>';

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

    protected static function getContent(&$block)
    {
        extract(static::$tokens);

        if ($token = match($textContent, $block))
        {
            return Block::lex("|$token[0]");
        }

        if ($token = match($variableContent, $block))
        {
            return Block::lex($token[0]);
        }

        if ($token = match($nodeContent, $block))
        {
            return Block::lex($token[1]);
        }
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
        return is_array($content) ? Block::parse($content) : $content;
    }
}
