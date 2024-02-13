<?php

namespace Tests\Integration;

use BankSystem\Exceptions\InsufficientFundsException;
use BankSystem\Exceptions\InvalidAmountException;
use BankSystem\Services\BankAccountService;
use BankSystem\Services\UserManager;
use PHPUnit\Framework\TestCase;

class FundTransferTest extends TestCase
{
    private string $name1;
    private string $email1;
    private string $name2;
    private string $email2;

    protected function setUp(): void
    {
        $this->name1 = 'Philip J. Fry';
        $this->email1 = 'philip.fry@planetexpress.com';
        $this->name2 = 'Turanga Leela';
        $this->email2 = 'turanga.leela@planetexpress.com';
    }

    public function testAccountsTransfer()
    {
        $userManager = new UserManager();
        $user1 = $userManager->createUser($this->name1, $this->email1);
        $user2 = $userManager->createUser($this->name2, $this->email2);

        $service = new BankAccountService();
        $account1 = $service->createAccount($user1, 1000.00);
        $account2 = $service->createAccount($user2, 1000.00);
        $account1PreviousBalance = $account1->getBalance();
        $account2PreviousBalance = $account2->getBalance();

        $transferAmountNegative = -500.00;
        $this->ExpectException(InvalidAmountException::class);
        $service->transfer($account1, $account2, $transferAmountNegative);

        $transferAmountZero = 0.00;
        $this->ExpectException(InvalidAmountException::class);
        $service->transfer($account1, $account2, $transferAmountZero);

        $transferAmountInsufficient = 1500.00;
        $this->ExpectException(InsufficientFundsException::class);
        $service->transfer($account1, $account2, $transferAmountInsufficient);

        $transferAmount = 500.00;
        $service->transfer($account1, $account2, $transferAmount);

        $this->assertEquals($account1PreviousBalance - $transferAmount, $account1->getBalance(), "The account balance did not match the expected value after transfer.");
        $this->assertEquals($account2PreviousBalance + $transferAmount, $account2->getBalance(), "The account balance did not match the expected value after transfer.");
    }
}
