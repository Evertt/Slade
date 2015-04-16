<?php

namespace Slade;

class Slade
{
    protected $file;
    protected $scope;
    public $parser;

    public static function parse($file, $scope = [], $sections = [])
    {
        $file = static::retrieveFile($file);

        if (is_array($scope)) {
            $scope = new Scope($scope);
        }

        if (is_array($sections)) {
            $sections = new Scope($sections);
        }

        return Parser::parse($file, $scope, $sections);
    }

    public static function retrieveFile($file)
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_values(array_filter($lines, 'trim'));
    }
}
