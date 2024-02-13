<?php

namespace BankSystem\Exceptions;

use Exception;

class AccountNotFoundException extends Exception {
  private $accountId;

  public function __construct($message = "Account not found", $code = 0, Exception $previous = null, $accountId = 0) {
    parent::__construct($message, $code, $previous);
  }

  public function getAccountId() {
    return $this->accountId;
  }
}