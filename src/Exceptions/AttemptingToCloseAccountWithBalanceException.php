<?php

namespace BankSystem\Exceptions;

use Exception;

class AttemptingToCloseAccountWithBalanceException extends Exception {
    private $accountId;

    public function __construct($message = "Attempting to close account with balance", $code = 0, Exception $previous = null, $accountId = 0) {
        parent::__construct($message, $code, $previous);
    }

    public function getAccountId() {
        return $this->accountId;
    }
}