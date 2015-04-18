<?php

namespace Slade;

class Scope implements \ArrayAccess
{
    protected $vars;
    protected $parent;

    public function __construct($vars = [], Scope $parent = null)
    {
        $this->vars = $vars;
        $this->parent = $parent;
    }

    public function get($var, $default = null)
    {
        $val = $this->_get($var);

        if (!$val && $this->parent) {
            $val = $this->parent->get($var);
        }

        return $val ?: $default;
    }

    public function set($var, $value)
    {
        $this->vars[$var] = $value;
    }

    protected function _get($var)
    {
        $val = $this->vars;
        $path = explode('.', $var);

        foreach ($path as $step) {
            if (!($val = $this->check($val, $step))) {
                return;
            }
        }

        return $val;
    }

    protected function check($var, $key)
    {
        $val = null;

        if (is_object($var)) {
            if (isset($var->$key)) {
                $val = $var->$key;
            }
        }

        if (is_array($var)) {
            if (isset($var[$key])) {
                $val = $var[$key];
            }
        }

        return $val;
    }

    public function offsetSet($offset, $val) {
        if (is_null($offset)) {
            $this->vars[] = $val;
        } else {
            $this->vars[$offset] = $val;
        }
    }

    public function offsetExists($offset) {
        return !!$this->get($offset);
    }

    public function offsetUnset($offset) {
        unset($this->vars[$offset]);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }
}
