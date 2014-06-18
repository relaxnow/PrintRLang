<?php

namespace RelaxNow\PrintRLang\V2;

class ArrayParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new ArrayParser(new RecursiveDescentParser("Array
(
   [Room] => E104
   [Difficulty] => 2
   [Type] => 1
)"));
        $this->assertEquals([
               'Room' => 'E104',
               'Difficulty' => '2',
               'Type' => '1',
            ],
            $parser->parse()
        );
    }
}