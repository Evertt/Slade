<?php namespace Slim;

class Parser {

    public static $nodes = [];
    public static $tidy;

    public static function initNodes() {
        foreach (glob(__DIR__ . '/nodes/*?Node.php') as $filename) {
            $class = 'Slim\Nodes\\' . basename($filename, '.php');
            $rc = new \ReflectionClass($class);
            preg_match('/@node (.+)/i', $rc->getDocComment(), $m);
            static::$nodes[$m[1]] = $class;
        }
    }

    public static function parse($lines = [], Scope $scope = null) {
        if (!static::$nodes)
            static::initNodes();

        if (is_string($lines))
            $lines = explode(PHP_EOL, $lines);

        $tree = static::getTopNodes($lines);
        $html = static::parseTree($tree, $scope);

        return $html;
    }

    protected static function getTopNodes(&$lines) {
        if (empty($lines)) return;

        $nodes = [];
        $root = static::getDepth(reset($lines));

        while($line = reset($lines)) {
            $depth = static::getDepth($line);

            if ($depth == $root)
                $nodes[] = trim(array_shift($lines));

            if ($depth > $root)
                $nodes[array_pop($nodes)] =
                    static::getInsides($lines, $root);

            if ($depth < $root) break;
        }

        return $nodes;
    }

    protected static function getInsides(&$lines, $d=0) {
        if (empty($lines)) return;

        $insides = '';
        $root = static::getDepth(reset($lines));

        while($line = reset($lines)) {
            $depth = static::getDepth($line);

            if ($depth < $root) break;

            $insides .= substr(array_shift($lines), $d) . PHP_EOL;
        }

        return rtrim($insides, PHP_EOL);
    }

    public static function getDepth($line) {
        return strlen($line) - strlen(ltrim($line));
    }

    protected static function parseTree($tree, Scope $scope = null) {
        $html = '';

        foreach($tree as $key => $value)
            $html .= static::parseNode($key, $value, $scope);

        return $html;
    }

    protected static function parseNode($key, $value, Scope $scope = null) {
        list($node, $innerNode)
            = static::extract($key, $value);

        foreach(static::$nodes as $pattern => $class)
            if (preg_match($pattern, $node))
                return $class::parse($node, $scope, $innerNode);
    }

    protected static function extract($node, $value) {
        $innerNode = '';

        if (is_int($node))
            $node = $value;
        else
            $innerNode = $value;

        return [$node, $innerNode];
    }

}