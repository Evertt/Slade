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

    static function makeTree($block)
    {
        extract(static::getTokens($block));

        $node         = Block::makeTree($node);

        $newLines     = count_new_lines($block);
        $block        = trim($block, "\n");
        $indentation  = measure_indentation($block);
        $block        = outdent($block, $indentation);

        $children     = Template::makeTree($block);

        return compact('iterable', 'individual', 'node', 'children', 'newLines');
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

    static function parseTree($tree)
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

        $node     = Block::parseBlock($node);

        $children = Template::parseTree($children);

        if ($children)
        {
            $children .= "\n";
        }

        $closing  = "<?php endforeach; ?>";

        return $opening  . $node . repeat("\n", $newLines[0]) .
               $children .
               $closing  . repeat("\n", $newLines[1]);
    }
}