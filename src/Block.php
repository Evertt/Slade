<?php namespace Slade;

class Block
{
    protected static $blocks = [];

    static function initBlocks()
    {
        foreach (glob(__DIR__.'/Blocks/*?Block.php') as $filename)
        {
            $class = 'Slade\Blocks\\'.basename($filename, '.php');

            $rc = new \ReflectionClass($class);

            preg_match('/@token (.+)/i', $rc->getDocComment(), $m);

            static::$blocks[$m[1]] = $class;
        }
    }

    static function lex($templateBlock)
    {
        if (!static::$blocks) static::initBlocks();

        $templateBlock = ltrim($templateBlock);

        foreach(static::$blocks as $token => $block)
        {
            if (preg_match($token, $templateBlock))
            {
                return [$block => $block::lex($templateBlock)];
            }
        }
    }

    static function parse($treeBlock)
    {
        if (empty($treeBlock)) return;

        $blockParser = key($treeBlock);

        return $blockParser::parse(current($treeBlock));
    }
}