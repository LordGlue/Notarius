<?php

class CategoryTest extends CTestCase
{

    public function testPushAndPop()
    {
        $stack = array();
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack) - 1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
        //   $this->assertEquals(0, 1);
    }

    public function testTruef()
    {
        $this->assertTrue(false == false, 'Тут ок');
     //   $this->assertTrue(false == true, 'Тут косяк');
    }
}