<?php

class Error {
    public function __construct($code, $message) {
        $this->code = $code;
        $this->message = $message;
    }
}
