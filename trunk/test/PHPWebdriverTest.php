<?php

require_once dirname(__FILE__) . '/../phpwebdriver/WebDriver.php';

/**
 * 
 * @author kolec
 * @version 1.0
 * @property WebDriver $webdriver
 */
class PHPWebDriverTest extends PHPUnit_Framework_TestCase {

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

    protected function setUp()
    {
        $selenium = $this->config['selenium'];
        $this->webdriver = new WebDriver($selenium['host'], $selenium['port']);
        $this->webdriver->connect($selenium['browser']);
    }

    protected function tearDown()
    {
        $this->webdriver->close();
    }
    
    public function testFindOptionElementInCombobox() {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::name, "sel1");
        $this->assertNotNull($element);
        $option3 = $element->findOptionElementByText("option 3");
        $this->assertNotNull($option3);
        $this->assertEquals($option3->getText(), "option 3");
        $this->assertFalse($option3->isSelected());
        $option3->click();
        $this->assertTrue($option3->isSelected());

        $option2 = $element->findOptionElementByValue("2");
        $this->assertNotNull($option2);
        $this->assertEquals($option2->getText(), "option 2");
        $this->assertFalse($option2->isSelected());
        $option2->click();
        $this->assertFalse($option3->isSelected());
        $this->assertTrue($option2->isSelected());
    }

    public function testExecute() {
        $this->webdriver->get($this->test_url);
        $result = $this->webdriver->executeScript("return sayHello('unitTest')", array());
        $this->assertEquals("hello unitTest !!!", $result);
    }

    public function testScreenShot() {
        $this->webdriver->get($this->test_url);
        $tmp_filename = "screenshot".uniqid().".png";
        //unlink($tmp_filename);
        $result = $this->webdriver->getScreenshotAndSaveToFile($tmp_filename);
        $this->assertTrue(file_exists($tmp_filename));
        $this->assertTrue(filesize($tmp_filename)>100);
        unlink($tmp_filename);
    }

    /**
     * @expectedException WebDriverException
     */
    public function testHandleError() {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::name, "12323233233aa");
    }

    public function testFindElemenInElementAndSelections() {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::name, "sel1");
        $this->assertNotNull($element);
        $options = $element->findElementsBy(LocatorStrategy::tagName, "option");
        $this->assertNotNull($options);
        $this->assertNotNull($options[2]);
        $this->assertEquals($options[2]->getText(), "option 3");
        $this->assertFalse($options[2]->isSelected());
        $options[2]->click();
        $this->assertTrue($options[2]->isSelected());
        $this->assertFalse($options[0]->isSelected());
    }

    public function testFindElementByXpath() {
        $this->webdriver->get($this->test_url);
        $option3 = $this->webdriver->findElementBy(LocatorStrategy::xpath, '//select[@name="sel1"]/option[normalize-space(text())="option 3"]');
        $this->assertNotNull($option3);
        $this->assertEquals($option3->getText(), "option 3");
        $this->assertFalse($option3->isSelected());
        $option3->click();
        $this->assertTrue($option3->isSelected());
    }


    public function testFindElementByAndSubmit() {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::id, "prod_name");
        $this->assertNotNull($element);
        $element->sendKeys(array("selenium 123"));
        $this->assertEquals($element->getValue(), "selenium 123");
        $element->clear();
        $this->assertEquals($element->getValue(), "");
        $element->sendKeys(array("selenium 123"));
        $element->submit();
        $element2 = $this->webdriver->findElementBy(LocatorStrategy::id, "result1");
        $this->assertNotNull($element2);
    }

    public function testGetPageAndUrl() {
        $this->webdriver->get($this->test_url);
        $this->assertEquals($this->webdriver->getTitle(), "Test page");
        $this->assertEquals($this->webdriver->getCurrentUrl(), $this->test_url);
    }

    public function testGetText() {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::name, "div1");
        $this->assertNotNull($element);
        $this->assertEquals($element->getText(), "lorem ipsum");
    }

    public function testGetName() {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::name, "div1");
        $this->assertNotNull($element);
        $this->assertEquals($element->getName(), "div");
    }

    public function testGetPageSource() {
        $this->webdriver->get($this->test_url);
        $src = $this->webdriver->getPageSource();
        $this->assertNotNull($src);
        $this->assertTrue(strpos($src, "<html>") == 0);
        $this->assertTrue(strpos($src, "<body>") > 0);
        $this->assertTrue(strpos($src, "div1") > 0);
    }


    public function testGetAttribute()
    {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::id, "attr-haver");
        $this->assertEquals($element->getAttribute('class'), 'has-none');
}

    public function testGetAttributeShouldReturnTrueWhenCheckedEqualsChecked()
    {
        $this->webdriver->get($this->test_url);
        $element = $this->webdriver->findElementBy(LocatorStrategy::id, "i-am-checked");
        $this->assertTrue($element->getAttribute('checked'));
    }

    public function testGetWindowHandlesShouldHaveOneItem() {
        $this->webdriver->get($this->test_url);
        $handles = $this->webdriver->getWindowHandles();
        $this->assertEquals(1, count($handles));
    }

    public function testGetWindowHandleShouldMatchFirstElementOfGetWindowHandles() {
        $this->webdriver->get($this->test_url);
        $handles = $this->webdriver->getWindowHandles();
        $this_window_handle = $this->webdriver->getWindowHandle();
        $this->assertEquals($handles[0], $this_window_handle);
    }


}

?>