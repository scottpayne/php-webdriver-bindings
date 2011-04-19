<?php

/**
 * WebDriverException 
 */
class WebDriverException extends Exception {

    public function __construct($message, $code, $previous) {
        parent::__construct($message, $code, $previous);
    }
}
?>
