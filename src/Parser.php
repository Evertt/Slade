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

        if (!file_exists($hash))
        {
            $template = Template::parseTemplate($file);
            
            /*$template = str_replace("?>\n", "?>\n\n", $template);*/

            array_map('unlink', glob(static::$compiledPath . "/$filename.*.php"));

            file_put_contents($hash, $template);
        }

        return $hash;
    }

    public static function render($filename, $__data = [])
    {
        extract($__data);
        $__env = Environment::getInstance();

        ob_start();
        include $filename;
        $view = ob_get_clean();

        //$view = preg_replace('/\n\s*\n\s*\n/', "\n\n", $view);

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
