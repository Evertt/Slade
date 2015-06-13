<?php namespace Slade\Blocks;

use Slade\Block;
use Slade\Template;

/**
 * @token /^-/
 */
class YieldBlock
{
    protected static $tokens = [
        'section'         => '/^-\s*(\S*)/',
        'textContent'     => '/^(?!=|:).+/',
        'variableContent' => '/^(==|=) *.+/',
        'nodeContent'     => '/^: *([\s\S]*?)(?=\n*$)/D'
    ];

    static function lex($block)
    {
        $section  = static::getSection($block);
        $content  = static::getContent($block);
        $children = Template::lex($block);

        return compact(
            'section',
            'content',
            'children'
        );
    }

    static function parse($tree)
    {
        extract($tree);

        $section  = addcslashes($section, '"');
        $content  = static::setContent($content);
        $children = Template::parse($children);

        $output  = "<?php ob_start() ?>$content$children";
        $output .= '<?php $__tmp = ob_get_clean(); ?>';
        $output .= '<?= $__env->yieldContent("'.$section.'") ?: $__tmp ?>';
        $output .= '<?php unset($__tmp); ?>';

        return $output;
    }

    protected static function getSection(&$block)
    {
        extract(static::$tokens);

        $token = match($section, $block);
        
        return $token ? $token[1] : null;
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

    protected static function setContent($content)
    {
        return is_array($content) ? Block::parseBlock($content) : $content;
    }
}
