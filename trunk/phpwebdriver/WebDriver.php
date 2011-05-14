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
require_once 'WebElement.php';
require_once 'WebDriverException.php';
require_once 'LocatorStrategy.php';

class WebDriver extends WebDriverBase {

    function __construct($host, $port) {
        parent::__construct("http://" . $host . ":" . $port . "/wd/hub");
    }

    public function connect($browserName="firefox") {
        $request = $this->requestURL . "/session";
        $session = curl_init($request);
        $postargs = "{ desiredCapabilities: {  browserName: '" . $browserName . "', javascriptEnabled: true, nativeEvents: true } } ";
        $this->preparePOST($session, $postargs);
        curl_setopt($session, CURLOPT_HEADER, true);
        $response = curl_exec($session);
        $header = curl_getinfo($session);
        $this->requestURL = $header['url'];
        //print_r($this->requestURL . "<br/>");
        curl_close($session);
    }

    /**
     * Delete the session.
     */
    public function close() {
        $request = $this->requestURL;
        $session = curl_init($request);
        $this->prepareDELETE($session);
        $response = curl_exec($session);
        curl_close($session);
    }

    /**
     * Navigate to a new URL
     * @param string $url The URL to navigate to.
     */
    public function get($url) {
        $request = $this->requestURL . "/url";
        $session = curl_init($request);
        $args = array('url' => $url);
        $this->preparePOST($session, json_encode($args, JSON_FORCE_OBJECT));
        $response = curl_exec($session);
        curl_close($session);
    }

    /**
     * Get the current page title.
     * @return string The current URL.
     */
    public function getCurrentUrl() {
        $response = $this->execute_rest_request_GET($this->requestURL . "/url");
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Get the current page title. 
     * @return string current page title
     */
    public function getTitle() {
        $response = $this->execute_rest_request_GET($this->requestURL . "/title");
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Get the current page source.
     * @return string page source 
     */
    public function getPageSource() {
        $request = $this->requestURL . "/source";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Get the current user input speed. The server should return one of {SLOW|MEDIUM|FAST}.
     * How these constants map to actual input speed is still browser specific and not covered by the wire protocol.
     * @return string {SLOW|MEDIUM|FAST}
     */
    public function getSpeed() {
        $request = $this->requestURL . "/speed";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
      Inject a snippet of JavaScript into the page for execution in the context of the currently selected frame.
     * The executed script is assumed to be synchronous and the result of evaluating the script
     * is returned to the client.
     * @return Object result of evaluating the script is returned to the client.
     */
    public function execute($script, $script_args) {
        $request = $this->requestURL . "/execute";
        $session = curl_init($request);
        $args = array('script' => $script, 'args' => $script_args);
        print_r($jsonData);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
        curl_close($session);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
      Inject a snippet of JavaScript into the page for execution in the context of the currently selected frame.
     * The executed script is assumed to be synchronous and the result of evaluating the script
     * is returned to the client.
     * @return Object result of evaluating the script is returned to the client.
     */
    public function executeScript($script, $script_args) {
        $request = $this->requestURL . "/execute";
        $session = curl_init($request);
        $args = array('script' => $script, 'args' => $script_args);
        $jsonData = json_encode($args);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
        curl_close($session);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
      Inject a snippet of JavaScript into the page for execution
     * in the context of the currently selected frame. The executed script
     * is assumed to be asynchronous and must signal that is done by invoking
     * the provided callback, which is always provided as the final argument
     * to the function. The value to this callback will be returned to the client.
     * @return Object result of evaluating the script is returned to the client.
     */
    public function executeAsyncScript($script, $script_args) {
        $request = $this->requestURL . "/execute_async";
        $session = curl_init($request);
        $args = array('script' => $script, 'args' => $script_args);
        $jsonData = json_encode($args);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
        curl_close($session);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Take a screenshot of the current page.
     * @return string The screenshot as a base64 encoded PNG.
     */
    public function getScreenshot() {
        $request = $this->requestURL . "/screenshot";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Take a screenshot of the current page and saves it to png file.
     * @param $png_filename filename (with path) where file has to be saved
     * @return bool result of operation (false if failure)
     */
    public function getScreenshotAndSaveToFile($png_filename) {
        $img = $this->getScreenshot();
        $data = base64_decode($img);
        $success = file_put_contents($png_filename, $data);
    }

}

?>