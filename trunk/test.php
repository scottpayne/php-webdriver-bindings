<?php
/*
Copyright 2011 3e software house & interactive agency

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/


require("Webdriver.php");
require("Keys.php");

$test_url = str_replace("test.php", "test_page.php", ($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));

$driver = new WebDriver("http://localhost:4444/wd/hub");
$driver->connect();                            
$driver->navigate($test_url);
$element = $driver->findElementBy("id", "prod_name");
$driver->sendKeys($element, array("selenium 123\n" ) );
//$driver->submit($element);

$element = $driver->findElementBy("id", "result1");
if ($element) {
  echo "getText = ".$driver->getText($element)."<br/>";
} else {
  echo "Element not found<br/>";
} 

$select = $driver->findElementsBy("name", "sel1");
if ($select) {
  print_r($select);
} 


$driver->close();

?>