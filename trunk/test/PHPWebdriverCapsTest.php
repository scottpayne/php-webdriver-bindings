<?php

require_once dirname(__FILE__) . '/../phpwebdriver/WebDriver.php';

/**
 * 
 * @author kolec
 * @version 1.0
 * @property WebDriver $webdriver
 */
class PHPWebDriverCapsTest extends PHPUnit_Framework_TestCase {

    private $config;
    private $test_url;

    public function __construct()
    {
        parent::__construct(func_get_args());
        $config = parse_ini_file(dirname(__FILE__) . '/../config/test.ini', true);
        if ($config) {
            $this->config = $config;
        } else {
            throw new RuntimeException("Couldn't parse test.ini file");
        }
        $this->test_url = $this->config['test_page']['url'];
    }

    protected function setUp() {
        $selenium = $this->config['selenium'];
        $this->webdriver = new WebDriver($selenium['host'], $selenium['port']);
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