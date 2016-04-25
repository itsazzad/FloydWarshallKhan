<?php
require_once('Keyboard.php');
class KeyboardTest extends PHPUnit_Framework_TestCase
{
	public function testKeyboard()
	{
		$keyboard = new Keyboard();
        $this->assertEquals('A', $keyboard->getCursor());
		$keyboard->moveCursor('↑');
        $this->assertEquals('`', $keyboard->getCursor());
		$keyboard->moveCursor('→');
        $this->assertEquals('~', $keyboard->getCursor());
		$keyboard->moveCursor('↓');
        $this->assertEquals('B', $keyboard->getCursor());
		$keyboard->moveCursor('←');
        $this->assertEquals('A', $keyboard->getCursor());
	}
}
?>