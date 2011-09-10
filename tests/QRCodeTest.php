<?php

require_once('../QRCode.php');

class QRCodeTest extends PHPUnit_Framework_TestCase 
{
  
  public function testArrayWithDefaultSize() {
    $result = QRCode::_retrieve_params(array('text' => 'testing',
                                             'size' => 'L'));
    $this->assertEquals(array('testing', '300x300', true),
                        $result);
  }
  
  public function testArrayWithNoSizeAndBorder() {
    $result = QRCode::_retrieve_params(array('text' => 'testing',
                                             'border' => false));
    $this->assertEquals(array('testing', '200x200', false),
                        $result);
  }
  
  public function testArrayWithIntSize() {
    $result = QRCode::_retrieve_params(array('text' => 'testing',
                                             'size' => 100));
    $this->assertEquals(array('testing', '100x100', true),
                        $result);
  }
  
  public function testArrayWithWidthAndHeight() {
    $result = QRCode::_retrieve_params(array('text' => 'testing',
                                             'width' => 100,
                                             'height' => 150));
    $this->assertEquals(array('testing', '100x150', true),
                        $result);
  }
  
  public function testParamsWithDefaultSize() {
    $result = QRCode::_retrieve_params('testing', 'L');
    $this->assertEquals(array('testing', '300x300', true),
                        $result);
  }
  
  public function testParamsWithIntSize() {
    $result = QRCode::_retrieve_params('testing', 100);
    $this->assertEquals(array('testing', '100x100', true),
                        $result);
  }
  
  public function testParamsWithNoSize() {
    $result = QRCode::_retrieve_params('testing');
    $this->assertEquals(array('testing', '200x200', true),
                        $result);
  }
  
  public function testParamsWithWidthAndHeight() {
    $result = QRCode::_retrieve_params('testing', 100, 150);
    $this->assertEquals(array('testing', '100x150', true),
                        $result);
  }
  
}