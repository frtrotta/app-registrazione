<?php

namespace modules\login;

/**
 * Malformed requests or invalid operation
 */
class ClientRequestException extends \Exception {
    public function __construct($message, $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
