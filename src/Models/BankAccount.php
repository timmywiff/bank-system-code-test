<?php

namespace BankSystem\Models;

use BankSystem\Interfaces\AccountOperations;
use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;

class BankAccount implements AccountOperations {
    private readonly int $accountId;
    private float $balance;
    private User $owner;

    private function __construct(int $accountId, User $owner, float $initialBalance = 0) {
        $this->accountId = $accountId;
        $this->owner = $owner;
        $this->balance = $initialBalance;
    }

    /**
     * Create a new bank account
     *
     * @param int $accountId
     * @param User $owner
     * @param float $initialBalance
     * @return BankAccount
     * @throws InvalidAmountException
     */
    public static function create(int $accountId, User $owner, float $initialBalance = 0): BankAccount {
        if ($initialBalance <= 0) {
            throw new InvalidAmountException();
        }
        return new BankAccount($accountId, $owner, $initialBalance);
    }

    public function deposit(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidAmountException;
        }
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidAmountException();
        }
        if ($amount > $this->balance) {
            throw new InsufficientFundsException();
        }
        $this->balance -= $amount;
    }

    public function getBalance(): float {
        return $this->balance;
    }

    /**
     * Gets the owner of the account
     *
     * @return User
     */
    public function getOwner(): User {
        return $this->owner;
    }

    /**
     * Gets the account id
     *
     * @return int
     */
    public function getAccountId(): int {
        return $this->accountId;
    }
}