<?php namespace Slade\Blocks;

use Slade\Block;
use Slade\Template;

/**
 * @token /^>/
 */
class IterationBlock
{
    protected static $tokens = [
        'iterable'   => '/^> +(.+?)((?= +>| *\:)|$)/m',
        'individual' => '/^> *(.*?)((?= *\:)|$)/m',
        'node'       => '/^: *([\s\S]*?)(?=\n*$)/D'
    ];

    static function lex($block)
    {
        extract(static::getTokens($block));

        $node     = Block::lex($node);
        $children = Template::lex($block);

        return compact('iterable', 'individual', 'node', 'children');
    }

    static function parse($tree)
    {
        extract($tree);

        if (!$individual)
        {
            $individual
                = preg_match('/^\$\w+$/', $iterable)
                ? '$' . singular(trim($iterable, '$'))
                : '$item';
        }

        if ($individual == $iterable)
        {
            $individual = '$item';
        }

        $opening  = "<?php foreach($iterable as \$i => $individual): ?>";
        $node     = Block::parse($node);
        $children = Template::parse($children);
        $closing  = "<?php endforeach; ?>";

        return $opening . $node . $children . $closing;
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