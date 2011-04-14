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

    function __construct($WebDriver, $element, $options) {
        parent::__construct($WebDriver->requestURL . "/element/" . $element->ELEMENT);
    }

    public function sendKeys($value) {
        if (!is_array($value)) {
            throw new Exception("$value must be an array");
        }
        $request = $this->requestURL . "/value";
        $session = curl_init($request);
        $postargs = "{'value':" . json_encode($value) . "}";
        print_r($postargs);
        $this->preparePOST($session, $postargs);
        $response = trim(curl_exec($session));
        curl_close($session);
    }

    public function clear() {
        $request = $this->requestURL . "/clear";
        $session = curl_init($request);
        $this->preparePOST($session, null);
        $response = trim(curl_exec($session));
        curl_close($session);
    }

    public function click() {
        $request = $this->requestURL . "/click";
        $session = curl_init($request);
        $this->preparePOST($session, null);
        $response = trim(curl_exec($session));
        curl_close($session);
    }

    public function submit() {
        $request = $this->requestURL . "/submit";
        $session = curl_init($request);
        $this->preparePOST($session, null);
        $response = trim(curl_exec($session));
        curl_close($session);
    }

    public function getText() {
        $request = $this->requestURL . "/text";
        $session = curl_init($request);
        $this->prepareGET($session);
        $response = curl_exec($session);
        curl_close($session);

        $response = json_decode(trim($response));
        if ($response && $response->value) {
            return $response->value;
        }
        return null;
    }

    public function getName() {
        $request = $this->requestURL . "/name";
        $session = curl_init($request);
        $this->prepareGET($session);
        $response = curl_exec($session);
        curl_close($session);

        $response = json_decode(trim($response));
        if ($response && $response->value) {
            return $response->value;
        }
        return null;
    }


}

?>