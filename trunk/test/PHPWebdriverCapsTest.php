<?php

require_once 'phpwebdriver/WebDriver.php';

/**
 * 
 * @author kolec
 * @version 1.0
 * @property WebDriver $webdriver
 */
class PHPWebDriverCapsTest extends PHPUnit_Framework_TestCase {

    private $test_url = "http://localhost:8080/php-webdriver-bindings/test_page.php";

    protected function setUp() {
        $this->webdriver = new WebDriver("localhost", 4444);
        $this->webdriver->connect("android", "", array(
			"platform"=>"ANDROID",
			"browserConnectionEnabled"=>true,
			"rotatable"=>true,
			"takesScreenshot"=>true,
			));
    }

    protected function tearDown() {
        $this->webdriver->close();
    }

    public function testGetText() {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::name, "div1");
        $this->assertNotNull($element);
        $this->assertEquals($element->getText(), "lorem ipsum");
    }

}

?>