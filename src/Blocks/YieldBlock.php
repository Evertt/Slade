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

    static function makeTree($block)
    {
        $section     = static::getSection($block);
        $content     = static::getContent($block);

        $newLines    = count_new_lines($block);
        $block       = trim($block, "\n");
        $indentation = measure_indentation($block);
        $block       = outdent($block, $indentation);

        $children    = Template::makeTree($block);

        return compact(
            'section',
            'content',
            'children',
            'indentation',
            'newLines'
        );
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

        $section = addcslashes($section, '"');

        $content = static::setContent($content);

        $children = Template::parseTree($children);

        if ($children)
        {
            $children = indent($children, $indentation) . "\n";
        }

        $result = "<?php \$__env->startYield(); ?>$content"
                . repeat("\n", $newLines[0])
                . $children
                . "<?php \$__env->endYield(\"$section\"); ?>"
                . repeat("\n", $newLines[1]);

        return $result;
    }

    protected static function setContent($content)
    {
        return is_array($content) ? Block::parseBlock($content) : $content;
    }
}
