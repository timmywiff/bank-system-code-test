<?php

namespace Tests\Unit\Services;

use BankSystem\Exceptions\AccountNotFoundException;
use BankSystem\Exceptions\AttemptingToCloseAccountWithBalanceException;
use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;
use BankSystem\Models\BankAccount;
use BankSystem\Models\User;
use BankSystem\Services\BankAccountService;
use PHPUnit\Framework\TestCase;

class BankAccountServiceTest extends TestCase
{
    private User $user;
    private User $anotherUser;

    protected function setUp(): void
    {
        $this->user = User::create(1, 'Philip J. Fry', 'philip.fry@planetexpress.com');
        $this->anotherUser = User::create(2, 'Turanga Leela', 'turanga.leela@planetexpress.com');
    }

    public function testCreateAccount(): void
    {
        $service = new BankAccountService();
        $initialBalance = 1234.56;

        $account = $service->createAccount($this->user, $initialBalance);

        $this->assertInstanceOf(BankAccount::class, $account, "Failed to create a BankAccount instance.");
        $this->assertEquals($this->user, $account->getOwner(), "The account owner did not match the expected value.");
        $this->assertEquals($initialBalance, $account->getBalance(), "The account balance did not match the expected value.");
        $this->assertContains($account, $service->getAccounts(), "createAccount() failed to add the account to the list of accounts.");
    }

    public function testCreateAccountNegativeAmount(): void
    {
        $service = new BankAccountService();

        $this->expectException(InvalidAmountException::class);
        $service->createAccount($this->user, -1234.56);
    }

    public function testCreateAccountZeroAmount(): void
    {
        $service = new BankAccountService();

        $this->expectException(InvalidAmountException::class);
        $service->createAccount($this->user, 0);
    }

    public function testGetAccountById(): void
    {
        $service = new BankAccountService();
        $account = $service->createAccount($this->user, 1234.56);
        $accountId = $account->getAccountId();

        $foundAccount = $service->getAccountById($accountId);

        $this->assertEquals($account, $foundAccount, "getAccountById() did not return the expected account.");
    }

    public function testGetAccountByIdNotFound(): void
    {
        $service = new BankAccountService();

        $this->expectException(AccountNotFoundException::class);
        $service->getAccountById(1);
    }

    public function testTransfer(): void
    {
        $service = new BankAccountService();
        $fromAccount = $service->createAccount($this->user, 1000);
        $toAccount = $service->createAccount($this->anotherUser, 1000);
        $previousFromBalance = $fromAccount->getBalance();
        $previousToBalance = $toAccount->getBalance();
        $amountToTransfer = 100;

        $service->transfer($fromAccount, $toAccount, $amountToTransfer);

        $this->assertEquals($previousFromBalance - $amountToTransfer, $fromAccount->getBalance(), "transfer() failed to update the balance of the source account.");
        $this->assertEquals($previousToBalance + $amountToTransfer, $toAccount->getBalance(), "transfer() failed to update the balance of the destination account.");
    }

    public function testTransferNegativeAmount(): void
    {
        $service = new BankAccountService();
        $fromAccount = $service->createAccount($this->user, 1000);
        $toAccount = $service->createAccount($this->anotherUser, 1000);

        $this->expectException(InvalidAmountException::class);
        $service->transfer($fromAccount, $toAccount, -100);
    }

    public function testTransferZeroAmount(): void
    {
        $service = new BankAccountService();
        $fromAccount = $service->createAccount($this->user, 1000);
        $toAccount = $service->createAccount($this->anotherUser, 1000);

        $this->expectException(InvalidAmountException::class);
        $service->transfer($fromAccount, $toAccount, 0);
    }

    public function testTransferInsufficientFunds(): void
    {
        $service = new BankAccountService();
        $fromAccount = $service->createAccount($this->user, 1000);
        $toAccount = $service->createAccount($this->anotherUser, 1000);

        $this->expectException(InsufficientFundsException::class);
        $service->transfer($fromAccount, $toAccount, 2000);
    }

    public function testCloseAccount(): void
    {
        $service = new BankAccountService();
        $account = $service->createAccount($this->user, 1000);

        $account->withdraw(1000);

        $service->closeAccount($account);

        $this->assertNotContains($account, $service->getAccounts(), "closeAccount() failed to remove the account from the list of accounts.");
    }

    public function testCloseAccountWithBalance(): void
    {
        $service = new BankAccountService();
        $account = $service->createAccount($this->user, 1000);

        $this->expectException(AttemptingToCloseAccountWithBalanceException::class);
        $service->closeAccount($account);
    }
}
