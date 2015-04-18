<?php

namespace Slade;

class Parser
{
    protected static $nodes = [];

    public static function initNodes()
    {
        foreach (glob(__DIR__.'/nodes/*?Node.php') as $filename) {
            $class = 'Slade\Nodes\\'.basename($filename, '.php');

            $rc = new \ReflectionClass($class);

            preg_match('/@node (.+)/i', $rc->getDocComment(), $m);

            static::$nodes[$m[1]] = $class;
        }
    }

    public static function parse($lines = [], Scope & $scope = null, Scope & $sections = null)
    {
        if (!static::$nodes) {
            static::initNodes();
        }

        if (is_string($lines)) {
            $lines = preg_split('/$\R*^\K/m', $lines);
        }

        if (!$scope) {
            $scope = new Scope();
        }

        if (!$sections) {
            $sections = new Scope();
        }

        $nodes = static::getTopNodes($lines);

        $html = static::parseNodes($nodes, $scope, $sections);

        return $html;
    }

    public static function getTopNodes($lines = [])
    {
        $nodes = [];

        while ($line = reset($lines)) {
            $depth = static::getDepth($line);

            if ($depth === 0) {
                $nodes[] = [
                    'node' => ltrim(array_shift($lines), ' '),
                    'inner' => '',
                    'depth' => 0
                ];
            }

            elseif ($depth > 0) {
                static::appendLinesToLastNode($nodes, $lines);
            }
        }

        return $nodes;
    }

    protected static function isEmpty($line) {
        return !trim($line, "\r\n");
    }

    protected static function appendLinesToLastNode(&$nodes, &$lines) {
        $inner = '';

        while ($node = &$nodes[max(array_keys($nodes))])
        {
            if (!static::isEmpty($node['node'])) break;

            $inner .= array_pop($nodes)['node'];
        }

        $appendix = static::getInsides($lines, $node['depth']);
        $node['inner'] .= $inner . $appendix['inner'];
        $node['depth'] = $appendix['depth'];
    }

    protected static function getInsides(&$lines, $d = 0)
    {
        $insides = '';
        $root = static::getDepth(reset($lines));

        while ($line = reset($lines)) {
            $depth = static::getDepth($line);

            if ($depth < $root) {
                break;
            }

            $insides .= substr(array_shift($lines), $d ?: $root);
        }

        return ['inner' => $insides, 'depth' => $d ?: $root];
    }

    public static function getDepth($line)
    {
        return strlen($line) - strlen(ltrim($line, ' '));
    }

    protected static function parseNodes($nodes, Scope & $scope, Scope & $sections)
    {
        $html = '';

        foreach ($nodes as $node)
        {
            $html .= static::parseNode($node['node'], $node['inner'], $node['depth'], $scope, $sections);
        }

        return $html;
    }

    protected static function parseNode($node, $inner, $depth, Scope & $scope, Scope & $sections)
    {
        foreach (static::$nodes as $pattern => $class)
        {
            if (preg_match($pattern, $node))
            {
                return $class::parse($node, $inner, $depth, $scope, $sections);
            }
        }

        return $node . $inner;
    }
}
