<?php

namespace RelaxNow\PrintRLang\V2;

/**
 * Parse a one-dimensional 'print_r' array.
 * @package RelaxNow\PrintRLang\V2
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
     * Parse a one-dimensional array.
     *
     * @return array
     */
    public function parse()
    {
        $result = array();

        // Array\n
        $this->arrayStart();$this->lf();
        // (\n
        $this->braceOpen();$this->lf();

        //     [key] => value
        while (!$this->parser->lookAhead(')')) {
            $result = $this->arrayAssign($result);
        }

        // )
        $this->braceClose();

        return $result;
    }

    /**
     * Consume an "Array" token.
     */
    private function arrayStart()
    {
        $this->parser->consume('Array');
    }

    /**
     * Consume a Line Feed token.
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

    /**
     * @param $result
     * @return mixed
     */
    private function arrayAssign($result)
    {
        // SPACE+
        $this->space();
        while ($this->parser->lookAhead(' ')) {
            $this->space();
        }

        // ARRAY_KEY
        $key = $this->arrayKey();

        // SPACE FAT_ARROW SPACE
        $this->space();
        $this->fatArrow();
        $this->space();

        // ARRAY_VALUE
        $value = $this->arrayValue();

        // \n
        $this->lf();

        // Process the result from what we've parsed.
        $result[$key] = $value;

        // And return it.
        return $result;
    }

    /**
     * Consume a space character.
     */
    private function space()
    {
        $this->parser->consume(' ');
    }

    /**
     * Consume a "=>" token.
     */
    private function fatArrow()
    {
        $this->parser->consume('=>');
    }

    /**
     * Consume an array key and return the value.
     *
     * @return string
     */
    private function arrayKey()
    {
        $this->bracketOpen();

        $key = $this->keyValue();

        $this->bracketClose();

        return $key;
    }

    /**
     * Consume a "[" character.
     */
    private function bracketOpen()
    {
        $this->parser->consume('[');
    }

    /**
     * Consume and return the actual value for an array key.
     *
     * @return string
     */
    private function keyValue()
    {
        return $this->parser->consumeRegex('[^\]]+');
    }

    /**
     * Consume the "]" character.
     */
    private function bracketClose()
    {
        $this->parser->consume(']');
    }

    /**
     * Consume and return the array value.
     *
     * @return string
     */
    private function arrayValue()
    {
        return $this->parser->consumeRegex("[^\n]+");
    }
}