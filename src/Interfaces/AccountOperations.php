<?php

namespace BankSystem\Interfaces;

use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;

interface AccountOperations
{
    /**
     * Deposits money into the account
     *
     * @param float $amount
     * @return void
     * @throws InvalidAmountException
     */
    public function deposit(float $amount): void;

    /**
     * Withdraws money from the account
     *
     * @param float $amount
     * @return void
     * @throws InvalidAmountException
     * @throws InsufficientFundsException
     */
    public function withdraw(float $amount): void;

    /**
     * Gets the balance of the account
     *
     * @return float
     */
    public function getBalance(): float;
}