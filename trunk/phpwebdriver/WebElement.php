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
require_once 'WebDriverBase.php';

class WebElement extends WebDriverBase {

    function __construct($parent, $element, $options) {
        if (get_class($parent) == 'WebDriver') {
            $root = $parent->requestURL;
        } else {
            $root = preg_replace("(/element/.*)", "", $parent->requestURL);
        }
        parent::__construct($root . "/element/" . $element->ELEMENT);
    }

    public function sendKeys($value) {
        if (!is_array($value)) {
            throw new Exception("$value must be an array");
        }
        $request = $this->requestURL . "/value";
        $session = $this->curlInit($request);
		$args = array( 'value'=>$value );
        $postargs =json_encode($args);
        $this->preparePOST($session, $postargs);
        $response = trim(curl_exec($session));
    }

    public function getValue() {
        $request = $this->requestURL . "/value";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    public function clear() {
        $request = $this->requestURL . "/clear";
        $session = $this->curlInit($request);
        $this->preparePOST($session, null);
        $response = trim(curl_exec($session));
    }

    public function click() {
        $request = $this->requestURL . "/click";
        $session = $this->curlInit($request);
        $this->preparePOST($session, null);
        $response = trim(curl_exec($session));
    }

    public function submit() {
        $request = $this->requestURL . "/submit";
        $session = $this->curlInit($request);
        $this->preparePOST($session, "");
        $response = trim(curl_exec($session));
    }

    public function getText() {
        $request = $this->requestURL . "/text";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    public function getName() {
        $request = $this->requestURL . "/name";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Determine if an OPTION element, or an INPUT element of type checkbox or radiobutton is currently selected.
     * @return boolean Whether the element is selected.
     */
    public function isSelected() {
        $request = $this->requestURL . "/selected";
        $response = $this->execute_rest_request_GET($request);
        $isSelected = $this->extractValueFromJsonResponse($response);
        return ($isSelected == 'true');
    }

    /**
     * Select an OPTION element, or an INPUT element of type checkbox or radiobutton.
     * 
     */
    public function setSelected() {
	$this->click(); //setSelected is now deprecated
    }


    /**
     * find OPTION by text in combobox
     * 
     */
    public function findOptionElementByText($text) {
        $option = $this->findElementBy(LocatorStrategy::xpath, 'option[normalize-space(text())="'.$text.'"]');
        return $option;
    }

    /**
     * find OPTION by value in combobox
     * 
     */
    public function findOptionElementByValue($val) {
        $option = $this->findElementBy(LocatorStrategy::xpath, 'option[@value="'.$val.'"]');
        return $option;
    }
   

    /**
     * Determine if an element is currently enabled
     * @return boolean Whether the element is enabled.
     */
    public function isEnabled() {
        $request = $this->requestURL . "/enabled";
        $response = $this->execute_rest_request_GET($request);
        $isSelected = $this->extractValueFromJsonResponse($response);
        return ($isSelected == 'true');
    }


}

?>