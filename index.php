<?php

require_once('vendor/autoload.php');

function pr($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

Slade\Slade::$templatePaths = [__DIR__ . '/templates'];

echo Slade\Slade::parse('index', [
    'title' => '<< An escaped title >>',
    'body' => '<strong>This is my unescaped body text</strong>',
    'year' => 2000,
    'author' => 'V&D',
    'items' => [
        ['name' => 'first', 'price' => 10],
        ['name' => 'second', 'price' => 20],
        ['name' => 'third', 'price' => 30]
    ],
    'post' => ['body' => '<em>very important</em>']
]);