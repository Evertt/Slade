<?php

use Doctrine\Common\Inflector\Inflector;

if ( ! function_exists('indent'))
{
    /**
     * Indent a block of text a specific number of spaces.
     *
     * @param  string  $str
     * @param  integer $depth
     * @return mixed
     */
    function indent($str, $depth)
    {
        return preg_replace("/^/m", str_repeat(' ', $depth), $str);
    }
}

if ( ! function_exists('outdent'))
{
    /**
     * Outdent a block of text a specific number of spaces.
     *
     * @param  string  $str
     * @param  integer $depth
     * @return mixed
     */
    function outdent($str, $depth)
    {
        return preg_replace("/^ {{$depth}}/m", '', $str);
    }
}

if ( ! function_exists('start'))
{
    /**
     * Start a string with a specific token, removing any excess tokens.
     *
     * @param  string $str
     * @param  string $token
     * @return string
     */
    function start($str, $token)
    {
        return $token . ltrim($str, $token);
    }
}

if ( ! function_exists('finish'))
{
    /**
     * Finish a string with a specific token, removing any excess tokens.
     *
     * @param  string $str
     * @param  string $token
     * @return string
     */
    function finish($str, $token)
    {
        return rtrim($str, $token) . $token;
    }
}

if ( ! function_exists('repeat'))
{
    /**
     * Repeat a string a specific number of times.
     *
     * @param  string  $str
     * @param  integer $amount
     * @return string
     */
    function repeat($str, $amount)
    {
        return str_repeat($str, $amount);
    }
}

if ( ! function_exists('surround'))
{
    /**
     * Surround string with a certain token,
     * removing any excess tokens.
     *
     * @param  string $str
     * @param  string $token
     * @return string
     */
    function surround($str, $token)
    {
        return $token . trim($str, $token) . $token;
    }
}

if ( ! function_exists('starts_with'))
{
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) === 0) return true;
        }

        return false;
    }
}

if ( ! function_exists('e'))
{
    /**
     * Escape HTML entities in a string.
     *
     * @param  string  $value
     * @return string
     */
    function e($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }
}

if ( ! function_exists('match_case'))
{
    /**
     * Attempt to match the case on two strings.
     *
     * @param  string  $value
     * @param  string  $comparison
     * @return string
     */
    function match_case($value, $comparison)
    {
        $functions = ['mb_strtolower', 'mb_strtoupper', 'ucfirst', 'ucwords'];

        foreach ($functions as $function)
        {
            if (call_user_func($function, $comparison) === $comparison)
            {
                return call_user_func($function, $value);
            }
        }

        return $value;
    }
}

if ( ! function_exists('singular'))
{
    /**
     * Get the singular form of an English word.
     *
     * @param  string  $value
     * @return string
     */
    function singular($value)
    {
        $singular = Inflector::singularize($value);

        return match_case($singular, $value);
    }
}