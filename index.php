<?php

require_once('vendor/autoload.php');

function pr($arr = []) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function he($str) {
    return htmlentities(html_entity_decode($str));
}

echo Slim\Slim::parse('templates/layout.slim', [
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