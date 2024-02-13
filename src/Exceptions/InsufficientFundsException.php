<?php

namespace BankSystem\Exceptions;

use Exception;

class InsufficientFundsException extends Exception {
  private $currentBalance;

  public function __construct($message = "Insufficient funds", $code = 0, Exception $previous = null, $currentBalance = 0) {
    parent::__construct($message, $code, $previous);
  }

  public function getCurrentBalance() {
    return $this->currentBalance;
  }
}