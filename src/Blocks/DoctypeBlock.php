<?php namespace Slade\Blocks;

/**
 * @token /^doctype/
 */
class DoctypeBlock
{
    protected static $tokens = [
        'doctype' => '/^doctype (\w*)/'
    ];

    static function lex($block)
    {
        $doctype = static::getDoctype($block);

        return compact('doctype');
    }

    static function parse($tree)
    {
        extract($tree);

        return static::parseDoctype($doctype);
    }

    protected static function getDoctype($block)
    {
        extract(static::$tokens);

        $token = match($doctype, $block);
        
        return $token ? $token[1] : null;
    }

    protected static function parseDoctype($doc)
    {
        if (!$doc || $doc == 'html' || $doc == 'html5') {
            return '<!DOCTYPE html>';
        }

        if ($doc == '1.1') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" '
                    . '"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
        }

        if ($doc == 'strict') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" '
                    . '"http://www.w3.org/TR/html4/strict.dtd">';
        }

        if ($doc == 'frameset') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" '
                    . '"http://www.w3.org/TR/html4/frameset.dtd">';
        }

        if ($doc == 'transitional') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" '
                    . '"http://www.w3.org/TR/html4/loose.dtd">';
        }

        if ($doc == 'mobile') {
            return '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" '
                    . '"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">';
        }

        if ($doc == 'basic') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" '
                    . '"http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">';
        }
    }
}