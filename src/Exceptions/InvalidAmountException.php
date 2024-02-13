<?php

namespace BankSystem\Exceptions;

use Exception;

class InvalidAmountException extends Exception {
  private $amount;

  public function __construct($message = "Invalid amount", $code = 0, Exception $previous = null, $amount = 0) {
    parent::__construct($message, $code, $previous);
  }

  public function getAmount() {
    return $this->amount;
  }
}