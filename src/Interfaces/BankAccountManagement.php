<?php

namespace BankSystem\Interfaces;

use BankSystem\Exceptions\AccountNotFoundException;
use BankSystem\Exceptions\AttemptingToCloseAccountWithBalanceException;
use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;
use BankSystem\Models\BankAccount;
use BankSystem\Models\User;

interface BankAccountManagement
{
    /**
     * Creates a new bank account in the system
     *
     * @param User $owner
     * @param float $initialBalance
     * @return BankAccount
     * @throws InvalidAmountException
     */
    public function createAccount(User $owner, float $initialBalance): BankAccount;

    /**
     * Closes a bank account in the system
     *
     * @param BankAccount $account
     * @return void
     * @throws AccountNotFoundException
     * @throws AttemptingToCloseAccountWithBalanceException
     */
    public function closeAccount(BankAccount $account): void;

    /**
     * Transfers money from one account to another
     *
     * @param BankAccount $fromAccount
     * @param BankAccount $toAccount
     * @param float $amount
     * @return void
     * @throws InsufficientFundsException
     * @throws InvalidAmountException
     */
    public function transfer(BankAccount $fromAccount, BankAccount $toAccount, float $amount): void;

    /**
     * Gets a bank account by its id
     *
     * @param int $accountId
     * @return BankAccount | null
     * @throws AccountNotFoundException
     */
    public function getAccountById(int $accountId): ?BankAccount;

}