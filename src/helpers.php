<?php

function indent($str, $depth) {
    return preg_replace("/^/m", str_repeat(' ', $depth), $str);
}

function outdent($str, $depth) {
    return preg_replace("/^ {{$depth}}/m", '', $str);
}

function start($str, $token) {
    return $token . ltrim($str, $token);
}

function finish($str, $token) {
    return rtrim($str, $token) . $token;
}

function repeat($str, $amount) {
    return str_repeat($str, $amount);
}

function surround($str, $token) {
    return $token . trim($str, $token) . $token;
}

function countNewLines($str) {
    return strlen($str) - strlen(rtrim($str, PHP_EOL));
}