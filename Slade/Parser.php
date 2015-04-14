<?php namespace Slade;

class Parser {

    protected static $nodes = [];

    public static function initNodes() {
        foreach (glob(__DIR__ . '/nodes/*?Node.php') as $filename) {
            $class = 'Slade\Nodes\\' . basename($filename, '.php');
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

        $nodes = static::getTopNodes($lines);
        $html = static::parseNodes($nodes, $scope);

        return $html;
    }

    protected static function getTopNodes(&$lines) {
        if (empty($lines)) return;

        $nodes = [];
        $root = static::getDepth(reset($lines));

        while($line = reset($lines)) {
            $depth = static::getDepth($line);

            if ($depth == $root)
                $nodes[] = [
                    'node' => trim(array_shift($lines)),
                    'inner' => ''
                ];

            if ($depth > $root)
                $nodes[max(array_keys($nodes))]['inner'] =
                    static::getInsides($lines, $root);
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

    protected static function parseNodes($nodes, Scope $scope = null) {
        $html = '';

        foreach($nodes as $node)
            $html .= static::parseNode($node['node'], $node['inner'], $scope);

        return $html;
    }

    protected static function parseNode($node, $inner, Scope $scope = null) {
        foreach(static::$nodes as $pattern => $class)
            if (preg_match($pattern, $node))
                return $class::parse($node, $scope, $inner);
    }
}