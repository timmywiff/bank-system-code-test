<?php

namespace Tests\Unit\Models;

use BankSystem\Models\BankAccount;
use BankSystem\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    private User $user;

    protected function setUp(): void
    {
        $this->user = User::create(1, 'Philip J. Fry', 'philip.fry@planetexpress.com');
    }

    public function testUserCreation(): void
    {
        $userId = '1';
        $name = 'Philip J. Fry';
        $email = 'philip.fry@planetexpress.com';
        $user = $this->user;

        $this->assertInstanceOf(User::class, $user, "Failed to create a User instance.");
        $this->assertEquals($userId, $user->getUserId(), "The user ID did not match the expected value.");
        $this->assertEquals($name, $user->getName(), "The user name did not match the expected value.");
        $this->assertEquals($email, $user->getEmail(), "The user email did not match the expected value.");
    }

    public function testSetName(): void
    {
        $user = $this->user;
        $newName = 'Bender Bending Rodriguez';
        $user->setName($newName);

        $this->assertEquals($newName, $user->getName(), "setName() failed to update the user's name.");
    }

    public function testAddAccount(): void
    {
        $user = $this->user;
        $account = BankAccount::create('1', $user, 1234.56);

        $user->addAccount($account);

        $this->assertContains($account, $user->getAccounts(), "addAccount() failed to add the account to the user.");
    }

    public function testGetAccountsEmpty(): void
    {
        $user = $this->user;
        $this->assertEmpty($user->getAccounts(), "getAccounts() did not return an empty array for a user with no accounts.");
    }
}