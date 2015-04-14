<?php namespace Slim;

class Scope {

    protected $vars;
    protected $parent;

    public function __construct($vars = [], Scope $parent = null) {
        $this->vars = $vars;
        $this->parent = $parent;
    }

    public function get($var, $default = null) {
        $val = $this->getVar($var);

        if (!$val && $this->parent)
            $val = $this->parent->get($var);

        return $val ?: $default;
    }

    protected function getVar($var) {
        $val = $this->vars;
        $path = explode('.', $var);

        foreach($path as $step)
            if (!($val = $this->put($val, $step)))
                return null;

        return $val;
    }

    protected function put($var, $key) {
        $val = null;

        if (is_object($var))
            if (isset($var->$key))
                    $val = $var->$key;

        if (is_array($var))
            if (isset($var[$key]))
                $val = $var[$key];

        return $val;
    }

}