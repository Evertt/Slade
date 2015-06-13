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

    static function lex($block)
    {
        extract(static::getTokens($block));

        $node     = Block::lex($node);
        $children = Template::lex($block);

        return compact('bool', 'statement', 'node', 'children');
    }

    static function parse($tree)
    {
        extract($tree);

        if ($bool == '!')
        {
            $statement = "! ( $statement )";
        }

        $node      = Block::parse($node);
        $children  = Template::parse($children);

        return "<?php if ($statement): ?>$node$children<?php endif; ?>";
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
