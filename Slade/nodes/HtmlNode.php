<?php namespace Slade\Nodes;

use Slade\Scope;
use Slade\Parser;

/**
 * @node /^</
 */
class HtmlNode extends Node {

    public static function parse($node, $inner, Scope &$scope, Scope &$sections) {
        $node .= PHP_EOL . $inner . PHP_EOL;
        
        static::replaceVars($node, $scope);

        return $node;
    }

    protected static function replaceVars(&$node, Scope $scope) {
        preg_match_all('/{{\s*(\w+)\s*}}/', $node, $escapedVars);
        foreach($escapedVars[1] as $i => $var)
            $node = str_replace($escapedVars[0][$i], he($scope->get($var)), $node);

        preg_match_all('/{!\s*(\w+)\s*!}/', $node, $unescapedVars);
        foreach($unescapedVars[1] as $i => $var)
            $node = str_replace($unescapedVars[0][$i], $scope->get($var), $node);
    }

}