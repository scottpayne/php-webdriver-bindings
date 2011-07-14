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
require_once 'SeleniumException.php';

class WebDriver extends WebDriverBase
{

    function __construct($host, $port)
    {
        parent::__construct("http://" . $host . ":" . $port . "/wd/hub");
    }

    public function connect($browserName = "firefox", $version = "", $caps = array())
    {
        $request = $this->requestURL . "/session";
        $session = $this->curlInit($request);
        $allCaps =
                array_merge(
                    array(
                         'javascriptEnabled' => true,
                         'nativeEvents' => false,
                    ),
                    $caps,
                    array(
                         'browserName' => $browserName,
                         'version' => $version,
                    )
                );
        $params = array('desiredCapabilities' => $allCaps);
        $postargs = json_encode($params);
        $this->preparePOST($session, $postargs);
        curl_setopt($session, CURLOPT_HEADER, true);
        $response = curl_exec($session);
        if (!$response) {
            throw new SeleniumException('Selenium server is not listening on ' . $this->requestURL, WebDriverResponseStatus::SeleniumServerUnavailable);
        }
        $header = curl_getinfo($session);
        $this->requestURL = $header['url'];
    }

    /**
     * Delete the session.
     */
    public function close()
    {
        $request = $this->requestURL;
        $session = $this->curlInit($request);
        $this->prepareDELETE($session);
        $response = curl_exec($session);
        $this->curlClose();
    }

    /**
     * Navigate to a new URL
     * @param string $url The URL to navigate to.
     */
    public function get($url)
    {
        $request = $this->requestURL . "/url";
        $session = $this->curlInit($request);
        $args = array('url' => $url);
        $this->preparePOST($session, json_encode($args));
        $response = curl_exec($session);
    }

    /**
     * Get the current page title.
     * @return string The current URL.
     */
    public function getCurrentUrl()
    {
        $response = $this->execute_rest_request_GET($this->requestURL . "/url");
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Get the current page title.
     * @return string current page title
     */
    public function getTitle()
    {
        $response = $this->execute_rest_request_GET($this->requestURL . "/title");
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Get the current page source.
     * @return string page source
     */
    public function getPageSource()
    {
        $request = $this->requestURL . "/source";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Get the current user input speed. The server should return one of {SLOW|MEDIUM|FAST}.
     * How these constants map to actual input speed is still browser specific and not covered by the wire protocol.
     * @return string {SLOW|MEDIUM|FAST}
     */
    public function getSpeed()
    {
        $request = $this->requestURL . "/speed";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    public function setSpeed($speed)
    {
        $request = $this->requestURL . "/speed";
        $session = $this->curlInit($request);
        $args = array('speed' => $speed);
        $jsonData = json_encode($args);
        //		print_r($jsonData);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
        return $this->extractValueFromJsonResponse($response);
    }


    /**
    Change focus to another window. The window to change focus to may be specified
    by its server assigned window handle, or by the value of its name attribute.
     */
    public function selectWindow($windowName)
    {
        $request = $this->requestURL . "/window";
        $session = $this->curlInit($request);
        $args = array('name' => $windowName);
        $jsonData = json_encode($args);
        //		print_r($jsonData);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
    Inject a snippet of JavaScript into the page for execution in the context of the currently selected frame.
     * The executed script is assumed to be synchronous and the result of evaluating the script
     * is returned to the client.
     * @return Object result of evaluating the script is returned to the client.
     */
    public function execute($script, $script_args)
    {
        $request = $this->requestURL . "/execute";
        $session = $this->curlInit($request);
        $args = array('script' => $script, 'args' => $script_args);
        $jsonData = json_encode($args);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
    Inject a snippet of JavaScript into the page for execution in the context of the currently selected frame.
     * The executed script is assumed to be synchronous and the result of evaluating the script
     * is returned to the client.
     * @return Object result of evaluating the script is returned to the client.
     */
    public function executeScript($script, $script_args)
    {
        $request = $this->requestURL . "/execute";
        $session = $this->curlInit($request);
        $args = array('script' => $script, 'args' => $script_args);
        $jsonData = json_encode($args);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
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
    public function executeAsyncScript($script, $script_args)
    {
        $request = $this->requestURL . "/execute_async";
        $session = $this->curlInit($request);
        $args = array('script' => $script, 'args' => $script_args);
        $jsonData = json_encode($args);
        $this->preparePOST($session, $jsonData);
        $response = curl_exec($session);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Take a screenshot of the current page.
     * @return string The screenshot as a base64 encoded PNG.
     */
    public function getScreenshot()
    {
        $request = $this->requestURL . "/screenshot";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Take a screenshot of the current page and saves it to png file.
     * @param $png_filename filename (with path) where file has to be saved
     * @return bool result of operation (false if failure)
     */
    public function getScreenshotAndSaveToFile($png_filename)
    {
        $img = $this->getScreenshot();
        $data = base64_decode($img);
        $success = file_put_contents($png_filename, $data);
    }

    /**
     * Return a set of window handles which can be used to iterate over all open windows
     * of this webdriver instance by passing them to #switchTo().window(String)
     *
     * @return A set of window handles which can be used to iterate over all open windows.
     */
    public function getWindowHandles()
    {
        $request = $this->requestURL . "/window_handles";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }

    /**
     * Return an opaque handle to this window that uniquely identifies it within this driver instance.
     * This can be used to switch to this window at a later date
     *
     * @return string
     */
    public function getWindowHandle()
    {
        $request = $this->requestURL . "/window_handle";
        $response = $this->execute_rest_request_GET($request);
        return $this->extractValueFromJsonResponse($response);
    }


}

?>