<?php

namespace RelaxNow\PrintRLang\V3;

use RelaxNow\PrintRLang\V2\RecursiveDescentParser;

class ArrayParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new ArrayParser(new RecursiveDescentParser("Array
(
    [Talk] => Array
        (
            [Title] => Ansible: Orchestrate your Infrastructure
            [Type] => 3
        )
)"));
        $this->assertEquals([
            'Talk' => [
                    'Title' => 'Ansible: Orchestrate your Infrastructure',
                    'Type' => '3',
                ],
            ],
            $parser->parse()
        );
    }
}