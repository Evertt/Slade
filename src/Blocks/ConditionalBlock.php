<?php namespace Slade\Blocks;

use Slade\Block;
use Slade\Template;

/**
 * @token /^\?|^!/
 */
class ConditionalBlock
{
    protected static $tokens = [
        'bool'      => '/^(\?|!)/',
        'statement' => '/^(.+(?=:\s)|.+$)/m',
        'node'      => '/^: *([\s\S]*?)(?=(\n)*$)/D'
    ];

    static function makeTree($block)
    {
        extract(static::getTokens($block));

        $node         = Block::makeTree($node);

        $newLines     = count_new_lines($block);
        $block        = trim($block, "\n");
        $indentation  = measure_indentation($block);
        $block        = outdent($block, $indentation);

        $children     = Template::makeTree($block);

        return compact('bool', 'statement', 'node', 'children', 'newLines');
    }

    static function parseTree($tree)
    {
        extract($tree);

        if ($bool == '!')
        {
            $statement = "! ( $statement )";
        }

        $statement = "<?php if ( $statement ): ?>";
        $node      = Block::parseBlock($node);
        $children  = Template::parseTree($children);

        if ($children)
        {
            $children .= "\n";
        }

        $result = $statement . $node
                . repeat("\n", $newLines[0])
                . $children
                . '<?php endif; ?>'
                . repeat("\n", $newLines[1]);

        return $result;
    }

    protected static function getTokens(&$block)
    {
        $tokens = [];

        foreach(static::$tokens as $name => $pattern)
        {
            $token = match($pattern, $block);
            
            $tokens[$name] = $token ? $token[1] : null;
        }

        return $tokens;
    }
}
