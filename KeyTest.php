<?php
require_once('Key.php');
class KeyTest extends PHPUnit_Framework_TestCase
{
	public function testKey()
	{
		$key = new Key('A',array(
			'→'=>'B',
			'←'=>'Z',
		));
		$expectation = array (
		  '↑' => NULL,
		  '→' => 'B',
		  '↓' => NULL,
		  '←' => 'Z',
		);
        $this->assertEquals($expectation, $key->getKey());
        $this->assertEquals(NULL, $key->getKey('↑'));
        $this->assertEquals('B', $key->getKey('→'));
        $this->assertEquals(NULL, $key->getKey('↓'));
        $this->assertEquals('Z', $key->getKey('←'));
		$key->update(array(
			'↑'=>'`',
			'↓'=>'a',
		));
		$expectation = array (
		'↑'=>'`',
		'→' => 'B',
		'↓'=>'a',
		'←' => 'Z',
		);
        $this->assertEquals($expectation, $key->getKey());
        $this->assertEquals('`', $key->getKey('↑'));
        $this->assertEquals('B', $key->getKey('→'));
        $this->assertEquals('a', $key->getKey('↓'));
        $this->assertEquals('Z', $key->getKey('←'));
	}
}
?>