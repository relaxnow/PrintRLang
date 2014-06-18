<?php

namespace RelaxNow\PrintRLang\V2;

/**
 * Simple Predictive Recursive Descent Parser with support for matching Regexps.
 * @package RelaxNow\PrintRLang\V2
 */
class RecursiveDescentParser
{
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function consume($terminal)
    {
        // Guard against syntax errors.
        if (!$this->lookAhead($terminal)) {
            $snippet = substr($this->content, 0, 10);
            throw new SyntaxException("Unable to match terminal: '$terminal' in output '$snippet...'.");
        }

        // Get the matched content.
        $matched = substr($this->content, 0, strlen($terminal));

        // Remove the content from the beginning of the string.
        $this->content = substr($this->content, strlen($matched));

        // Return what we matched.
        return $matched;
    }

    public function consumeRegex($terminalRegex)
    {
        // Guard against syntax errors.
        if (!$this->lookAheadRegex($terminalRegex)) {
            $terminalRegex = $this->completeRegex($terminalRegex);
            $snippet = substr($this->content, 0, 10);
            throw new SyntaxException(
                "Unable to match terminal regex: '" . $terminalRegex . "' in output '$snippet'"
            );
        }

        $terminalRegex = $this->completeRegex($terminalRegex);
        $matches = array();
        preg_match($terminalRegex, $this->content, $matches);

        if (!isset($matches[0]) || strpos($this->content, $matches[0]) !== 0 || count($matches) > 1) {
            throw new \LogicException(
                "No match found for terminal regex '$terminalRegex' or match not at start of line or multiple matches"
            );
        }

        $this->content = substr($this->content, strlen($matches[0]));

        return $matches[0];
    }

    public function lookAhead($terminal)
    {
        // Does the content start with the given terminal?
        return strpos($this->content, $terminal) ===0;
    }

    public function lookAheadRegex($terminalRegex)
    {
        $terminalRegex = $this->completeRegex($terminalRegex);

        return preg_match($terminalRegex, $this->content) > 0;
    }

    /**
     * For convenience sake we set the delimiters for the parser and specify that it should match only the start of
     * the content.
     * Note that the parser MUST NOT use a unescaped /.
     *
     * @param string $terminalRegex
     * @return string
     */
    private function completeRegex($terminalRegex)
    {
        return '/^' . $terminalRegex . '/';
    }
}

class SyntaxException extends \RuntimeException {}