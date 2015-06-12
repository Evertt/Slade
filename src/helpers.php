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

if ( ! function_exists('measure_indentation'))
{
    /**
     * Measure indentation
     *
     * @param  string  $str
     * @return integer
     */
    function measure_indentation($str)
    {
        return strlen($str) - strlen(ltrim($str, ' '));
    }
}

if ( ! function_exists('consume'))
{
    /**
     * Delete match from the beginning of the string
     *
     * @param  string  $str
     * @param  integer $depth
     * @return string
     */
    function consume($haystack, $match)
    {
        return ltrim(substr($haystack, strlen($match)), ' ');
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

if ( ! function_exists('match'))
{
    /**
     * Match token and delete it
     *
     * @param  string  $token
     * @param  string  $str
     * @return string|void
     */
    function match($token, &$str)
    {
        if (preg_match($token, $str, $match))
        {
            $str = consume($str, $match[0]);

            return $match;
        }
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

function count_new_lines($str)
{
    $newLinesAtStart = $newLinesAtEnd = 0;

    preg_match_all('/(\r\n?|\n\r?)+$/', $str, $newLines);

    if ($newLines[0])
    {
        $newLinesAtEnd = strlen($newLines[0][0]) / strlen($newLines[1][0]);
    }

    $str = preg_replace('/(\r\n?|\n\r?)+$/', '', $str);
    preg_match_all('/^(\r\n?|\n\r?)+/', $str, $newLines);

    if ($newLines[0])
    {
        $newLinesAtStart = strlen($newLines[0][0]) / strlen($newLines[1][0]);
    }

    return [$newLinesAtStart, $newLinesAtEnd];
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