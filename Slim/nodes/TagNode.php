<?php namespace Slim\Nodes;

use Slim\Parser;
use Slim\Scope;

/**
 * @node /^[a-z.#]/
 */
class TagNode extends Node {

    const self_closing_tags = [
        'area','base','br','col','command','embed','hr','img',
        'input','keygen','link','meta','param','source','track','wbr'
    ];

    protected static $defaultTagName = 'div';

    public static function parse($node, Scope $scope, $inner) {
        if (substr($node,0,7) == 'doctype')
            return static::parseDoctype(substr($node,0,8));

        $depth = Parser::getDepth(explode(PHP_EOL, $inner)[0]);

        $attributes = '';
        $parts = preg_split('/\s+(?=[^\t\r\n\f \/>"\'=]+=("[^"]+"|\S+))|(^[\w-#.]+|[^\t\r\n\f \/>"\'=]+=("[^"]+"|\S+))\K\s+/', $node);
        list($tagName, $id, $class) = static::split(array_shift($parts));

        $attributes .= $id ? " id=\"$id\"" : '';
        $attributes .= $class ? " class=\"$class\"" : '';

        while(static::isAttribute(reset($parts)))
            $attributes .= ' ' . static::getAttribute($parts, $scope);

        $content = end($parts);

        $attributes = static::combineClasses($attributes);

        if (substr($content,0,1) == '=')
            $content = trim(VariableNode::parse($content, $scope, null));

        $openingTag = "<$tagName$attributes>";
        $closingTag = "</$tagName>";

        if (static::isSelfClosingTag($tagName))
            return $openingTag . PHP_EOL;

        $infix = Parser::parse($inner, $scope);

        //*
        if ($infix) {
            $infix = static::addIndentation($infix, $depth);

            if ($content)
                $content = str_pad('', $depth) . $content;
        }

        return str_replace(PHP_EOL.PHP_EOL.PHP_EOL,PHP_EOL.PHP_EOL,
            ($infix ? PHP_EOL . $openingTag . PHP_EOL : $openingTag) .
            ($infix && $content ? $content . PHP_EOL : $content) .
            ($infix ? trim($infix, PHP_EOL) . PHP_EOL : '') .
            $closingTag . ($infix ? PHP_EOL : '') . PHP_EOL);
        /**/

        return $openingTag.$content.$infix.$closingTag;
    }

    protected static function split($tag) {
        preg_match('/^([\w-]+)?(?:#([\w-]+))?(?:\.([\w-.]+))?$/', $tag, $matches);

        list($match, $tagName, $id, $class) = $matches + array_fill(0, 4, '');

        if (!$tagName) $tagName = static::$defaultTagName;

        $class = str_replace('.', ' ', $class);

        return [$tagName, $id, $class];
    }

    protected static function isAttribute($attr) {
        return !!preg_match('/^[^\t\r\n\f \/>"\'=]+=.+/', $attr);
    }

    protected static function matchLiteralAttribute($attr, &$m) {
        return !!preg_match('/^([^\t\r\n\f \/>"\'=]+)="([^"]+)"/', $attr, $m);
    }

    protected static function matchBooleanAttribute($attr, &$m) {
        return !!preg_match('/^([^\t\r\n\f \/>"\'=]+)=(true|false)/', $attr, $m);
    }

    protected static function matchVariableAttribute($attr, &$m) {
        return !!preg_match('/^([^\t\r\n\f \/>"\'=]+)=(.+)/', $attr, $m);
    }

    protected static function getAttribute(&$parts, Scope $scope) {
        $attr = array_shift($parts);
        $m = [];

        if (static::matchLiteralAttribute($attr, $m))
            return $m[1] . '="' . he($m[2]) . '"';

        if (static::matchBooleanAttribute($attr, $m))
            return $m[2] === 'true' ? $m[1] : '';

        if (static::matchVariableAttribute($attr, $m))
            return $m[1] . '="' . he($scope->get($m[2])) . '"';
    }

    protected static function combineClasses($attributes) {
        $classes = [];
        preg_match_all('/class="([^"]+)"/', $attributes, $matches);
        
        foreach($matches[1] as $class)
            $classes = array_merge($classes, explode(' ', trim($class)));

        $classes = implode(' ', $classes);

        $attributes = preg_replace(
            '/class="[^"]+"/',
            'class="' . $classes . '"',
            $attributes,
            1
        );

        $attributes = preg_replace(
            '/ class="(?!'.$classes.')[^"]*"/',
            '',
            $attributes
        );

        return $attributes;
    }

    protected static function parseDoctype($doc) {
        if ($doc == 'html' || $doc = 'html5')
            return '<!DOCTYPE html>';

        if ($doc == '1.1')
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" '
                    + '"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';

        if ($doc == 'strict')
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" '
                    + '"http://www.w3.org/TR/html4/strict.dtd">';

        if ($doc == 'frameset')
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" '
                    + '"http://www.w3.org/TR/html4/frameset.dtd">';

        if ($doc == 'transitional')
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" '
                    + '"http://www.w3.org/TR/html4/loose.dtd">';

        if ($doc == 'mobile')
            return '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" '
                    + '"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">';

        if ($doc == 'basic')
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" '
                    + '"http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">';
    }

    protected static function isSelfClosingTag($tag) {
        return array_search($tag, static::self_closing_tags) !== false;
    }

    protected static function formatAttributeSpaces($attributes) {
        $attributes = trim(str_replace('  ', ' ', $attributes));
        return $attributes ? " $attributes" : '';
    }

    protected static function addIndentation($string, $depth) {
        $lines = explode(PHP_EOL, $string);

        foreach($lines as &$line)
            if ($line)
                $line = str_pad('', $depth) . $line;

        return implode(PHP_EOL, $lines);
    }

}