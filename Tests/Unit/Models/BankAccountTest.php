<?php

namespace Tests\Unit\Models;

use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;
use BankSystem\Models\BankAccount;
use BankSystem\Models\User;
use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = User::create(1, 'Philip J. Fry', 'philip.fry@planetexpress.com');
    }

    public function testAccountCreation(): void
    {
        $accountId = 1;
        $owner = $this->user;
        $initialBalance = 1234.56;

        $account = BankAccount::create($accountId, $owner, $initialBalance);

        $this->assertInstanceOf(BankAccount::class, $account, "Failed to create a BankAccount instance.");
        $this->assertEquals($accountId, $account->getAccountId(), "The account ID did not match the expected value.");
        $this->assertEquals($owner, $account->getOwner(), "The account owner did not match the expected value.");
        $this->assertEquals($initialBalance, $account->getBalance(), "The account balance did not match the expected value.");
    }

    public function testAccountCreationInvalidAmount(): void
    {
        $this->expectException(InvalidAmountException::class);
        BankAccount::create(2, $this->user, -1234.56);
    }

    public function testDeposit(): void
    {
        $account = BankAccount::create(2, $this->user, 1);
        $previousBalance = $account->getBalance();
        $amountToDeposit = 1000.00;

        $account->deposit($amountToDeposit);

        $this->assertEquals($amountToDeposit + $previousBalance, $account->getBalance(), "deposit() failed to update the account balance.");
    }

    public function testDepositInvalidAmount(): void
    {
        $account = BankAccount::create(3, $this->user, 1000);

        $this->expectException(InvalidAmountException::class);
        $account->deposit(-100);
    }


    public function testWithdraw(): void
    {
        $account = BankAccount::create(3, $this->user, 1000);
        $previousBalance = $account->getBalance();
        $amountToWithdraw = 500.00;

        $account->withdraw($amountToWithdraw);

        $this->assertEquals($previousBalance - $amountToWithdraw, $account->getBalance(), "withdraw() failed to update the account balance.");
    }

    public function testWithdrawInvalidAmount(): void
    {
        $account = BankAccount::create(4, $this->user, 1000);

        $this->expectException(InvalidAmountException::class);
        $account->withdraw(-100);
    }

    public function testWithdrawInsufficientFunds(): void
    {
        $account = BankAccount::create(5, $this->user, 1000);

        $this->expectException(InsufficientFundsException::class);
        $account->withdraw(1000.01);
    }
}
