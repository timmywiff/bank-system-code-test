<?php

namespace Tests\Integration;

use BankSystem\Exceptions\AttemptingToCloseAccountWithBalanceException;
use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;
use BankSystem\Models\User;
use BankSystem\Services\BankAccountService;
use BankSystem\Services\UserManager;
use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    private string $name;
    private string $email;

    protected function setUp(): void
    {
        $this->name = 'Philip J. Fry';
        $this->email = 'philip.fry@planetexpress.com';
    }

    public function testAccountBuildUpAndDownForNewUser()
    {
        $userManager = new UserManager();
        $user = $userManager->createUser($this->name, $this->email);

        $this->assertInstanceOf(User::class, $user, "Failed to create a User instance.");

        $initialNegativeBalance = -1234.56;
        $initialZeroBalance = 0.00;
        $initialBalance = 1234.56;
        $service = new BankAccountService();

        $this->expectException(InvalidAmountException::class);
        $service->createAccount($user, $initialNegativeBalance);

        $this->expectException(InvalidAmountException::class);
        $service->createAccount($user, $initialZeroBalance);

        $account = $service->createAccount($user, $initialBalance);

        $this->assertEquals($user, $account->getOwner(), "The account owner did not match the expected value.");
        $this->assertEquals($initialBalance, $account->getBalance(), "The account balance did not match the expected value.");
        $this->assertContains($account, $service->getAccounts(), "createAccount() failed to add the account to the list of accounts.");

        $negativeDepositAmount = -1000.00;
        $zeroDepositAmount = 0.00;
        $depositAmount = 1000.00;

        $this->expectException(InvalidAmountException::class);
        $account->deposit($negativeDepositAmount);

        $this->expectException(InvalidAmountException::class);
        $account->deposit($zeroDepositAmount);

        $account->deposit($depositAmount);

        $this->assertEquals($initialBalance + $depositAmount, $account->getBalance(), "The account balance did not match the expected value after deposit.");

        $negativeWithdrawAmount = -500.00;
        $zeroWithdrawAmount = 0.00;
        $insufficientFundsWithdrawAmount = 2000.00;
        $withdrawAmount = 500.00;

        $this->expectException(InvalidAmountException::class);
        $account->withdraw($negativeWithdrawAmount);

        $this->expectException(InvalidAmountException::class);
        $account->withdraw($zeroWithdrawAmount);

        $this->expectException(InsufficientFundsException::class);
        $account->withdraw($insufficientFundsWithdrawAmount);

        $account->withdraw($withdrawAmount);

        $this->assertEquals($initialBalance + $depositAmount - $withdrawAmount, $account->getBalance(), "The account balance did not match the expected value after withdrawal.");

        $this->expectException(AttemptingToCloseAccountWithBalanceException::class);
        $service->closeAccount($account);

        $currentBalance = $account->getBalance();
        $account->withdraw($currentBalance);
        $service->closeAccount($account);

        $this->assertNotContains($account, $service->getAccounts(), "closeAccount() failed to remove the account from the list of accounts.");
    }
}
