<?php //phpinfo();die;

require_once('vendor/autoload.php');

Slade\Parser::$templatePaths = [__DIR__ . '/templates'];
Slade\Parser::$compiledPath  = './compiled';

$name = 'Evert';
$age = 24;
$foods = ['salmon', 'lasagne', 'meat'];

$html = Slade\Slade::make('test.index', compact('name','age','foods'));

echo $html;