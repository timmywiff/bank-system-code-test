<?php

namespace BankSystem\Exceptions;

use Exception;

class UserNotFoundException extends Exception {
    private $userId;

    public function __construct($message = "User not found", $code = 0, Exception $previous = null, $userId = 0) {
        parent::__construct($message, $code, $previous);
    }

    public function getUserId() {
        return $this->userId;
    }
}