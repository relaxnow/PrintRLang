<?php

namespace RelaxNow\PrintRLang\V3;

use RelaxNow\PrintRLang\V2\RecursiveDescentParser;

class ArrayParser
{
    /**
     * @var RecursiveDescentParser
     */
    private $parser;

    /**
     * @param $parser
     */
    public function __construct(RecursiveDescentParser $parser)
    {
        $this->parser = $parser;
    }

    public function parse()
    {
        $result = array();

        $this->arrayStart();
        $this->lf();
        $this->spacesOptional();
        $this->braceOpen();
        $this->lf();
        while ($this->parser->lookAheadRegex('\s+\[')) {
            $result = $this->arrayAssign($result);
        }
        $this->spacesOptional();
        $this->braceClose();

        return $result;
    }

    private function arrayStart()
    {
        $this->parser->consume('Array');
    }

    private function lf()
    {
        $this->parser->consume("\n");
    }

    private function braceOpen()
    {
        $this->parser->consume('(');
    }

    private function braceClose()
    {
        $this->parser->consume(')');
    }

    private function arrayAssign($result)
    {
        $this->spacesRequired();

        $key = $this->arrayKey();

        $this->space();
        $this->fatArrow();
        $this->space();

        $value = $this->arrayValue();

        $this->lf();

        $result[$key] = $value;

        return $result;
    }

    private function space()
    {
        $this->parser->consume(' ');
    }

    private function fatArrow()
    {
        $this->parser->consume('=>');
    }

    private function arrayKey()
    {
        $this->bracketOpen();

        $key = $this->keyValue();

        $this->bracketClose();

        return $key;
    }

    private function bracketOpen()
    {
        $this->parser->consume('[');
    }

    private function keyValue()
    {
        return $this->parser->consumeRegex('[^\]]+');
    }

    private function bracketClose()
    {
        $this->parser->consume(']');
    }

    private function arrayValue()
    {
        if ($this->parser->lookAhead('Array')) {
            return $this->parse();
        }
        else {
            return $this->parser->consumeRegex("[^\n]+");
        }
    }

    private function spacesRequired()
    {
        $this->space();
        $this->spacesOptional();
    }

    private function spacesOptional()
    {
        while ($this->parser->lookAhead(' ')) {
            $this->space();
        }
    }
}