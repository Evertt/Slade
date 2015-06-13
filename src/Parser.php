<?php namespace Slade;

class Parser
{
    public static $templatePaths = [];
    public static $compiledPath = '';

    public static function make($filename, $data = [])
    {
        $compiled = static::compile($filename);

        return static::render($compiled, $data);
    }

    public static function compile($filename)
    {
        $file = static::retrieveFile($filename);

        $hash = static::$compiledPath . "/$filename." . sha1($file) . '.php';

        if (!file_exists($hash) or 1)
        {
            $template = Template::compile($file);

            array_map('unlink', glob(static::$compiledPath . "/$filename.*.php"));

            file_put_contents($hash, $template);
        }

        return $hash;
    }

    public static function render($filename, $__data = [])
    {
        extract($__data);
        $__fn  = function($v) {return $v;};
        $__env = Environment::getInstance();

        ob_start();
        include $filename;
        $view = ob_get_clean();

        return $view;
    }

    public static function retrieveFile($filename)
    {
        $filename = str_replace('.', '/', $filename);
        $filename .= '.slade';

        foreach(static::$templatePaths as $path)
        {
            if ($file = file_exists("$path/$filename"))
            {
                return file_get_contents("$path/$filename");
            }
        }
    }
}
