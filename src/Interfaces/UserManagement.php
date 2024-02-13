<?php

namespace BankSystem\Interfaces;

use BankSystem\Exceptions\UserNotFoundException;
use BankSystem\Models\User;

interface UserManagement
{
    /**
     * Creates a new user in the system
     *
     * @param string $name
     * @param string $email
     * @return User
     */
    public function createUser(string $name, string $email): User;

    /**
     * Updates an exiting user in the system
     *
     * @param User $user
     * @param string $name
     * @param string $email
     * @return User
     */
    public function updateUser(User $user, string $name, string $email): User;

    /**
     * Gets a user by their id
     *
     * @param int $userId
     * @return User | null
     * @throws UserNotFoundException
     */
    public function getUserById(int $userId): ?User;

    /**
     * Gets a user by their email
     *
     * @param string $email
     * @return User | null
     * @throws UserNotFoundException
     */
    public function getUserByEmail(string $email): ?User;

    /**
     * Gets a user by their name
     *
     * @param string $name
     * @return User | null
     * @throws UserNotFoundException
     */
    public function getUserByName(string $name): ?User;

    /**
     * Deletes a user from the system
     *
     * @param User $user
     * @return void
     * @throws UserNotFoundException
     */
    public function deleteUser(User $user): void;
}
