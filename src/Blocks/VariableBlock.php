<?php namespace Slade\Blocks;

/**
 * @token /^=/
 */
class VariableBlock
{
    protected static $tokens = [
        'unescaped' => '/^={2} *([^|\r\n]*?)((?= *\|.*$)| *$)/m',
        'escaped'   => '/^={1} *([^|\r\n]*?)((?= *\|.*$)| *$)/m',
        'text'      => '/^\|.*/m'
    ];

    static function makeTree($block)
    {
        $newLines = count_new_lines($block);
        $block    = trim($block, "\n");
        $variable = static::getVariable($block);
        $text     = static::getText($block);

        return $variable + compact('text', 'newLines');
    }

    protected static function getVariable(&$block)
    {
        extract(static::$tokens);

        if ($token = match($unescaped, $block))
        {
            return ['unescaped' => $token[1] ?: $token[2]];
        }

        if ($token = match($escaped, $block))
        {
            return ['escaped' => $token[1] ?: $token[2]];
        }
    }

    protected static function getText(&$block)
    {
        extract(static::$tokens);

        $token = match($text, $block);
        
        return $token ? ltrim($token[0], '| ') : null;
    }

    static function parseTree($tree)
    {
        extract($tree);

        if (isset($escaped))
        {
            $result = static::formatted($escaped, $text, true);
        }

        if (isset($unescaped))
        {
            $result = static::formatted($unescaped, $text, false);
        }

        return $result . repeat("\n", $newLines[1]);
    }

    protected static function formatted($var, $text, $escaped)
    {
        $output = $var ? ($escaped ? "e($var)" : $var) : null;

        if (is_null($text))
        {
            return "<?= $output ?>";
        }

        $text = addcslashes($text, '"');
        $text = TextBlock::replaceFunc($text);
        $text = $escaped ? "e(\"$text\")" : "\"$text\"";

        if (is_null($output))
        {
            return "<?= $text ?>";
        }

        if (starts_with($var, '$'))
        {
            return "<?= isset($var) ? $output : $text ?>";
        }

        return "<?= $output ?: $text ?>";
    }
}