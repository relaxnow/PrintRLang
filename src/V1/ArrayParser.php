<?php

namespace RelaxNow\PrintRLang\V1;

/**
 * Parse a empty 'print_r' array.
 * @package RelaxNow\PrintRLang\V1
 */
class ArrayParser
{
    /**
     * @var RecursiveDescentParser
     */
    private $parser;

    /**
     * @param RecursiveDescentParser $parser
     */
    public function __construct(RecursiveDescentParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parse an array and return the result.
     *
     * @return array
     */
    public function parse()
    {
        // Array\n
        $this->arrayStart();$this->lf();
        // (\n
        $this->braceOpen();$this->lf();
        //)
        $this->braceClose();
        return array();
    }

    /**
     * Consume an "Array" token.
     */
    private function arrayStart()
    {
        $this->parser->consume('Array');
    }

    /**
     * Consume a line feed character.
     */
    private function lf()
    {
        $this->parser->consume("\n");
    }

    /**
     * Consume a "(" character.
     */
    private function braceOpen()
    {
        $this->parser->consume('(');
    }

    /**
     * Consume a ")" character.
     */
    private function braceClose()
    {
        $this->parser->consume(')');
    }
}