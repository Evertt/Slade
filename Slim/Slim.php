<?php namespace Slim;

class Slim {

    protected $file;
    protected $scope;
    public $parser;

    public static function parse($file, $data = []) {
        $file = static::retrieveFile($file);

        if ($data instanceof Scope)
            $scope = $data;
        else
            $scope = new Scope($data);

        return Parser::parse($file, $scope);
    }

    protected static function retrieveFile($file) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_values(array_filter($lines, 'trim'));
    }

}