<?php namespace Slade;

use Illuminate\View\Compilers\Compiler as BaseCompiler;
use Illuminate\View\Compilers\CompilerInterface;

class Compiler extends BaseCompiler implements CompilerInterface
{

    /**
     * Get the path to the compiled version of a view.
     *
     * @param  string  $path
     * @return string
     */
    public function getCompiledPath($path)
    {
        $viewPath = app('config')->get('view.paths')[0];

        $sub = str_replace($viewPath, '', $path);

        $sub = str_replace(base_path(), '', $sub);

        $dotted = str_replace(['/', '\\'], '.', $sub);

        return $this->cachePath . '/' . trim($dotted, '.');
    }

    /**
     * Compile the view at the given path.
     *
     * @param  string $path
     * @return void
     */
    public function compile($path)
    {
        $file     = $this->files->get($path);
        $contents = Template::compile($file);

        if (!is_null($this->cachePath))
        {
            $compiledPath = $this->getCompiledPath($path);

            $this->files->put($compiledPath, $contents);
        }
    }
}