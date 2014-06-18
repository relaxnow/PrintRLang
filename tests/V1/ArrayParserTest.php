<?php

namespace RelaxNow\PrintRLang\V1;

class ArrayParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParserSuccess()
    {
        $parser = new ArrayParser(
            new RecursiveDescentParser(
                "Array\n(\n)"
            )
        );
        $parsed = $parser->parse();

        $this->assertTrue(is_array($parsed));
        $this->assertEmpty($parsed);
    }

    public function testParserFail()
    {
        $parser = new ArrayParser(
            new RecursiveDescentParser(
                "Array\n\n(\n)"
            )
        );

        $e = null;
        try {
            $parser->parse();
        }
        catch (SyntaxException $e) { }

        $this->assertNotEmpty($e);
    }
}