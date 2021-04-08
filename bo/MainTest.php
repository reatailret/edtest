<?php
require 'Function.php';
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    public function testAll()
    {
        $text = 'Здесь 3 слова, слова слова';
        $result = wordStat($text);
        $this->assertArrayHasKey('слова', $result);
        $this->assertEquals($result['слова'],3);

        $text = "d'être d'être d'être n'affecte n'affecte Un langage qui  pas votre manière de penser la programmation ne vaut pas la peine  connu.";
        $assertWord = "dêtre";
        $result = wordStat($text);
        $this->assertArrayHasKey($assertWord, $result);
        $this->assertEquals($result[$assertWord],3);
        

        
    }
}