<?php namespace Slade;

class Environment extends Singleton
{
    protected $extensions = [];
    protected $sections   = [];
    protected $yields     = [];
    protected $includes   = [];

    public function startExtension()
    {
        return $this->start('extensions');
    }

    public function endExtension($name, $data = [])
    {
        $result = $this->end('extensions', $name);
        echo Parser::make($name, $data);
    }

    public function startSection()
    {
        return $this->start('sections');
    }

    public function endSection($name)
    {
        $result = $this->end('sections', $name);
    }

    public function startYield()
    {
        return $this->start('yields');
    }

    public function endYield($name)
    {
        $result = $this->end('yields', $name);

        echo
            isset($this->sections[$name])
            ? $this->sections[$name]
            : $result;
    }

    public function startInclude()
    {
        return $this->start('includes');
    }

    public function endInclude($name, $data = [])
    {
        $result = $this->end('includes', $name);
        $include = Parser::make($name, $data);

        echo $include ?: $result;
    }

    protected function start($kind)
    {
        ob_start();
    }

    protected function end($kind, $name)
    {
        $this->{$kind}[$name] = ob_get_clean();

        return $this->{$kind}[$name];

    }
}