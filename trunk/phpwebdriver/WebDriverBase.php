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

    protected function prepareGET($session) {
        curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8"));
        //curl_setopt($session, CURLOPT_GET, true);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
    }

    protected function prepareDELETE($session) {
        curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8"));
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
    }

    public function findElementBy($locatorStrategy, $value) {
        $request = $this->requestURL . "/element";
        $session = curl_init($request);
        $postargs = "{'using':'" . $locatorStrategy . "', 'value':'" . $value . "'}";
        $this->preparePOST($session, $postargs);
        $response = trim(curl_exec($session));
        print_r($response . "<br/>");
        $json_response = json_decode($response);
        $element = $json_response->{'value'};
        curl_close($session);
        if (!$element && !$element->ELEMENT) {
            return null;
        }
        return new WebElement($this, $element, null);
    }

    public function findElementsBy($locatorStrategy, $value) {
        $request = $this->requestURL . "/elements";
        $session = curl_init($request);
        $postargs = "{'using':'" . $locatorStrategy . "', 'value':'" . $value . "'}";
        $this->preparePOST($session, $postargs);
        $response = trim(curl_exec($session));
        print_r($response . "<br/>");
        $json_response = json_decode($response);
        $element = $json_response->{'value'};
        curl_close($session);
        return $element;
    }

}

?>