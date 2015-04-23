<?php namespace Slade;

class Slade
{
    public static $templatePaths = [];

    public static function parse($fileName, $scope = [], $sections = [])
    {
        $file = static::retrieveFile($fileName);

        if (is_array($scope)) {
            $scope = new Scope($scope);
        }

        if (is_array($sections)) {
            $sections = new Scope($sections);
        }

        return Parser::parse($file, $scope, $sections);
    }

    public static function retrieveFile($fileName)
    {
        $fileName = str_replace('.', '/', $fileName);
        $fileName .= '.slade';

        foreach(static::$templatePaths as $path)
        {
            if ($file = file_get_contents("$path/$fileName"))
            {
                return $file;
            }
        }
    }
}
