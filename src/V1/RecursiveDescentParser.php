<?php

namespace RelaxNow\PrintRLang\V1;

/**
 * Simple Predictive Recursive Descent Parser.
 * @package RelaxNow\PrintRLang\V1
 */
class RecursiveDescentParser
{
    /**
     * What is left to parse.
     *
     * Note that this could also have been a stream, or some wrapper around a stream to make it more memory efficient.
     * And should you want to use a tokenizer / lexer this would be a token stream.
     *
     * @var string
     */
    private $content;

    /**
     * Input to parse.
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Match the given string in our content and remove it from the start, if the required string can not be found
     * then throw a SyntaxException.
     *
     * @param $terminal
     * @return string
     * @throws SyntaxException
     */
    public function consume($terminal)
    {
        // Guard against syntax errors.
        if (!$this->lookAhead($terminal)) {
            throw new SyntaxException("Unable to match terminal: '$terminal' in output.");
        }

        // Get the matched content.
        $matched = substr($this->content, 0, strlen($terminal));

        // Remove the content from the beginning of the string.
        $this->content = substr($this->content, strlen($matched));

        // Return what we matched.
        return $matched;
    }

    public function lookAhead($terminal)
    {
        // Does the content start with the given terminal?
        return strpos($this->content, $terminal) === 0;
    }
}

class SyntaxException extends \RuntimeException {}