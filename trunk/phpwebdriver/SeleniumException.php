<?php

require_once 'WebDriverException.php';

class SeleniumException extends WebDriverException
{
    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code);
    }
}
