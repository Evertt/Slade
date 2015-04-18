<?php

require_once('Slade/helpers.php');
require_once('vendor/autoload.php');

function pr($arr = []) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

$slade = 
'a
  b
    c

d
  e
    f

    g';
$slade = preg_split('/(?<=\n)/', $slade);
//var_dump($slade, Slade\Parser::getTopNodes($slade));

//*
echo Slade\Slade::parse('templates/index.slade', [
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
/**/