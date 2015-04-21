<?php

namespace Slade;

class TemplateBlock
{
    protected $prefix;
    protected $line;
    protected $insides;
    protected $postfix;
    protected $newLines;
    protected $indentation;

    public function __construct($block)
    {
        $line = strtok($block, "\r\n");
        $insides = str_replace($line, null, $block);
        $newLines = $this->countNewLines($insides);
        $insides = trim($insides, "\r\n");

        $indentation = $this->measureIndentation($insides);
        $insides = outdent($insides, $indentation);

        $this->line = $line;
        $this->insides = $insides;
        $this->newLines = $newLines;
        $this->indentation = $indentation;
    }

    protected function countNewLines($str)
    {
        $newLinesAtStart = $newLinesAtEnd = 0;

        preg_match_all('/^(\r\n?|\n\r?)+/', $str, $newLines);

        if ($newLines[0])
        {
            $newLinesAtStart = strlen($newLines[0][0]) / strlen($newLines[1][0]);
        }

        $str = preg_replace('/^(\r\n?|\n\r?)+/', '', $str);
        preg_match_all('/(\r\n?|\n\r?)+$/', $str, $newLines);

        if ($newLines[0])
        {
            $newLinesAtEnd = strlen($newLines[0][0]) / strlen($newLines[1][0]);
        }

        return [$newLinesAtStart, $newLinesAtEnd];
    }

    protected function measureIndentation($str)
    {
        return strlen($str) - strlen(ltrim($str, ' '));
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setLine($newLine)
    {
        $this->line = $newLine;
    }

    public function removeLine()
    {
        $this->line = '';
        $this->newLines[0] = 0;
    }
    public function stripLine()
    {
        $this->line = trim(
            $this->line,
            $this->line[0] . ' '
        );

        return $this->line;
    }

    public function getInsides()
    {
        return $this->insides;
    }

    public function setInsides($newInsides)
    {
        $this->insides = $newInsides;
    }

    public function getNewLines()
    {
        return $this->newLines;
    }

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function postfix($postfix)
    {
        $this->postfix = $postfix;
    }

    public function parseInsides(Scope $scope = null, Scope $sections = null)
    {
        return $this->insides = Parser::parse($this->insides, $scope, $sections);
    }

    public function indentInsides()
    {
        if ($this->insides)
        {
            indent($this->insides, $this->indentation);
        }

        return $this->insides;
    }

    public function wrap($prefix, $postfix)
    {
        $this->prefix = $prefix;

        if ($this->insides)
        {
            $this->insides .= PHP_EOL;
        }

        $this->postfix = $postfix;
    }

    public function __toString()
    {
        return
            $this->prefix .
            $this->line .
            repeat(PHP_EOL, $this->newLines[0]) .
            $this->insides .
            $this->postfix .
            repeat(PHP_EOL, $this->newLines[1]);
    }
}
