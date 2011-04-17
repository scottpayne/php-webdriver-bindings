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

require_once 'WebElement.php';

class WebDriverBase {

    protected $requestURL;

    function __construct($_seleniumUrl) {
        $this->requestURL = $_seleniumUrl;
    }

    protected function preparePOST($session, $postargs) {
        curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8"));
        curl_setopt($session, CURLOPT_POST, true);
        if ($postargs) {
            curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
        }
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
    }

    /**
     * Execute POST request
     * @param string $request URL REST request
     * @param string $postargs POST data
     * @return string $response Response from POST request
     */
    protected function execute_rest_request_POST($request, $postargs) {
        $session = curl_init($request);
        $this->preparePOST($session, $postargs);
        $response = trim(curl_exec($session));
        curl_close($session);
        return $response;
    }

    protected function prepareGET($session) {
        curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8"));
        //curl_setopt($session, CURLOPT_GET, true);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
    }

    /**
     * Execute GET request
     * @param string $request URL REST request 
     * @return string $response Response from GET request
     */
    protected function execute_rest_request_GET($request) {
        $session = curl_init($request);
        $this->prepareGET($session);
        $response = curl_exec($session);
        curl_close($session);
        return $response;
    }

    protected function prepareDELETE($session) {
        curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8"));
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
    }

    /**
     * Search for an element on the page, starting from the document root. 
     * @param string $locatorStrategy
     * @param string $value
     * @return WebElement found element
     */
    public function findElementBy($locatorStrategy, $value) {
        $request = $this->requestURL . "/element";
        $session = curl_init($request);
        //$postargs = "{'using':'" . $locatorStrategy . "', 'value':'" . $value . "'}";
	$args = array('using'=>$locatorStrategy, 'value'=>$value);
	$postargs = json_encode($args, JSON_FORCE_OBJECT);
        $this->preparePOST($session, $postargs);
        $response = trim(curl_exec($session));
        $json_response = json_decode($response);
	if (!$json_response) {
		return null;
	}
        $element = $json_response->{'value'};
        curl_close($session);
        if (!$element && !$element->ELEMENT) {
            return null;
        }
        return new WebElement($this, $element, null);
    }

    /**
     * 	Search for multiple elements on the page, starting from the document root. 
     * @param string $locatorStrategy
     * @param string $value
     * @return array of WebElement
     */
    public function findElementsBy($locatorStrategy, $value) {
        $request = $this->requestURL . "/elements";
        $session = curl_init($request);
        $postargs = "{'using':'" . $locatorStrategy . "', 'value':'" . $value . "'}";
        $this->preparePOST($session, $postargs);
        $response = trim(curl_exec($session));
        $json_response = json_decode($response);
        $elements = $json_response->{'value'};
        curl_close($session);
        $webelements = array();
        foreach ($elements as $key => $element) {
            $webelements[] = new WebElement($this, $element, null);
        }
        return $webelements;
    }

    /**
     * Function returns value of 'value' attribute in JSON string
     * @example extractValueFromJsonResponse("{'name':'John', 'value':'123'}")=='123'
     * @param string $json JSON string with value attrubute to extract
     * @return string value of 'value' attribute
     */
    public function extractValueFromJsonResponse($json) {
        $json = json_decode(trim($json));
        if ($json && $json->value) {
            return $json->value;
        }
        return null;
    }

}

?>