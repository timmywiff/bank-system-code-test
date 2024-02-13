<?php

namespace Tests\Unit\Services;

use BankSystem\Exceptions\UserNotFoundException;
use BankSystem\Services\UserManager;
use BankSystem\Models\User;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    private string $name;
    private string $email;

    protected function setUp(): void
    {
        $this->name = 'Philip J. Fry';
        $this->email = 'philip.fry@planetexpress.com';
    }

    public function testCreateUser()
    {
        $userManager = new UserManager();
        $user = $userManager->createUser($this->name, $this->email);

        $this->assertInstanceOf(User::class, $user, "Failed to create a User instance.");
        $this->assertEquals($this->name, $user->getName(), "The user name did not match the expected value.");
        $this->assertEquals($this->email, $user->getEmail(), "The user email did not match the expected value.");
        $this->assertContains($user, $userManager->getUsers(), "createUser() failed to add the user to the list of users.");
    }

    public function testGetUserById()
    {
        $userManager = new UserManager();
        $user = $userManager->createUser($this->name, $this->email);
        $userId = $user->getUserId();

        $foundUser = $userManager->getUserById($userId);

        $this->assertEquals($user, $foundUser, "getUserById() did not return the expected user.");
    }

    public function testGetUserByIdNotFound()
    {
        $userManager = new UserManager();

        $this->expectException(UserNotFoundException::class);
        $userManager->getUserById(1);
    }

    public function testDeleteUser()
    {
        $userManager = new UserManager();
        $user = $userManager->createUser($this->name, $this->email);

        $userManager->deleteUser($user);

        $this->assertNotContains($user, $userManager->getUsers(), "deleteUser() failed to remove the user from the list of users.");
    }
}