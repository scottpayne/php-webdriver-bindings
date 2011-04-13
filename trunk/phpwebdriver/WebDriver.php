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


class WebDriver {
  private $selUrl;
  
  
  function __construct($_seleniumUrl) {
       $this->selUrl = $_seleniumUrl;
  }  

  private function preparePOST($session, $postargs) {
	curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8")); 
	curl_setopt($session, CURLOPT_POST, true);
	if ($postargs) {
		curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs); 
	}
	curl_setopt($session, CURLOPT_HEADER, false); 
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
  }

  private function prepareGET($session) {
	curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8")); 
	//curl_setopt($session, CURLOPT_GET, true);
	curl_setopt($session, CURLOPT_HEADER, false); 
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
  }

  private function prepareDELETE($session) {
	curl_setopt($session, CURLOPT_HTTPHEADER, array("application/json;charset=UTF-8")); 
	curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($session, CURLOPT_HEADER, false); 
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
  }

  public function connect() {
	$request = $this->selUrl."/session";
	$session = curl_init($request); 
	$postargs = "{ desiredCapabilities: {  browserName: 'chrome', javascriptEnabled: true, nativeEvents: true } } ";
        $this->preparePOST($session, $postargs);
	curl_setopt($session, CURLOPT_HEADER, true); 
  	$response = curl_exec($session); 
	$header  = curl_getinfo( $session ); 
	$this->selUrl = $header['url'];
	print_r($this->selUrl."<br/>");

  	curl_close($session); 
  } 

  public function close() {
	$request = $this->selUrl;
	$session = curl_init($request); 
        $this->prepareDELETE($session);
  	$response = curl_exec($session); 
  	curl_close($session); 
  } 

  public function navigate($url) {
	$request = $this->selUrl."/url";
	$session = curl_init($request); 
	$postargs = "{'url':'".$url."'}";
	$this->preparePOST($session, $postargs);
  	$response = curl_exec($session); 
	print_r($response."<br/>");
  	curl_close($session); 	
  }

  public function findElementBy($locatorStrategy, $value) {
	$request = $this->selUrl."/element";
	$session = curl_init($request); 
	$postargs = "{'using':'".$locatorStrategy."', 'value':'".$value."'}";
	$this->preparePOST($session, $postargs);
  	$response = trim(curl_exec($session));
	print_r($response."<br/>"); 
	$json_response = json_decode($response);
	$element = $json_response->{'value'};	
  	curl_close($session); 	
	if (!$element && !$element->ELEMENT) {
		return null;
	}
	return $element;
  }

  public function findElementsBy($locatorStrategy, $value) {
	$request = $this->selUrl."/elements";
	$session = curl_init($request); 
	$postargs = "{'using':'".$locatorStrategy."', 'value':'".$value."'}";
	$this->preparePOST($session, $postargs);
  	$response = trim(curl_exec($session));
	print_r($response."<br/>"); 
	$json_response = json_decode($response);
	$element = $json_response->{'value'};	
  	curl_close($session); 	
	return $element;
  }

  public function sendKeys($element, $value) {
	if (!is_array($value)) {
		throw new Exception("$value must be an array");
	}
	$request = $this->selUrl."/element/".$element->ELEMENT."/value";
	$session = curl_init($request);
	$postargs = "{'value':".json_encode($value)."}";
	print_r($postargs);
	$this->preparePOST($session, $postargs);
  	$response = trim(curl_exec($session)); 
  	curl_close($session); 	
  }


  public function clear($element) {
	$request = $this->selUrl."/element/".$element->ELEMENT."/clear";
	$session = curl_init($request);
	$this->preparePOST($session, null);
  	$response = trim(curl_exec($session)); 
  	curl_close($session); 	
  }

  public function click($element) {
	$request = $this->selUrl."/element/".$element->ELEMENT."/click";
	$session = curl_init($request);
	$this->preparePOST($session, null);
  	$response = trim(curl_exec($session)); 
  	curl_close($session); 	
  }

  public function submit($element) {
	$request = $this->selUrl."/element/".$element->ELEMENT."/submit";
	$session = curl_init($request);
	$this->preparePOST($session, null);
  	$response = trim(curl_exec($session)); 
  	curl_close($session); 	
  }

  public function getText($element) {
	$request = $this->selUrl."/element/".$element->ELEMENT."/text";
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

  public function getName($element) {
	$request = $this->selUrl."/element/".$element->ELEMENT."/name";
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