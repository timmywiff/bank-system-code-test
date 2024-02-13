<?php

namespace BankSystem\Services;

use BankSystem\Exceptions\AccountNotFoundException;
use BankSystem\Exceptions\AttemptingToCloseAccountWithBalanceException;
use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;
use BankSystem\Interfaces\BankAccountManagement;
use BankSystem\Models\BankAccount;
use BankSystem\Models\User;


class BankAccountService implements BankAccountManagement {
    private array $accounts = [];

    public function createAccount(User $owner, float $initialBalance): BankAccount {
        $accountId = $this->generateAccountId();

        try {
            $account = BankAccount::create($accountId, $owner, $initialBalance);
            $owner->addAccount($account);
            $this->accounts[] = $account;
            return $account;
        } catch (InvalidAmountException $e) {
            throw new InvalidAmountException();
        }
    }

    public function transfer(BankAccount $fromAccount, BankAccount $toAccount, float $amount): void {
        if ($amount <= 0) {
            throw new InvalidAmountException();
        }

        if ($fromAccount->getBalance() < $amount) {
            throw new InsufficientFundsException();
        }

        $fromAccount->withdraw($amount);
        $toAccount->deposit($amount);
    }

    public function closeAccount(BankAccount $account): void {
        $foundAccount = null;
        $index = null;

        foreach ($this->accounts as $key => $a) {
            if ($a->getAccountId() === $account->getAccountId()) {
                $foundAccount = $a;
                $index = $key;
            }
        }

        if ($foundAccount === null) {
            throw new AccountNotFoundException();
        }

        if ($foundAccount->getBalance() > 0) {
            throw new AttemptingToCloseAccountWithBalanceException();
        }

        unset($this->accounts[$index]);
    }

    public function getAccountById(int $accountId): ?BankAccount {
        foreach ($this->accounts as $account) {
            if ($account->getAccountId() === $accountId) {
                return $account;
            }
        }
        throw new AccountNotFoundException();
    }

    /**
     * Get all accounts
     *
     * @return array
     */
    public function getAccounts(): array {
        return $this->accounts;
    }

    /**
     * Generates a new account id
     *
     * @return int
     */
    protected function generateAccountId(): int {
        return count($this->accounts) + 1;
    }
}