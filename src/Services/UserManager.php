<?php

namespace BankSystem\Services;

use BankSystem\Exceptions\UserNotFoundException;
use BankSystem\Interfaces\UserManagement;
use BankSystem\Models\User;

class UserManager implements UserManagement {
    private array $users = [];

    public function createUser(string $name, string $email): User {
        $userId = $this->generateUserId();
        $user = User::create($userId, $name, $email);
        $this->users[] = $user;
        return $user;
    }

    public function updateUser(User $user, string $name, string $email): User {
        $user->setName($name);
        $user->setEmail($email);
        return $user;
    }

    public function getUserById(int $userId): ?User {
        foreach ($this->users as $user) {
            if ($user->getUserId() === $userId) {
                return $user;
            }
        }
        throw new UserNotFoundException();
    }

    public function getUserByEmail(string $email): ?User {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        throw new UserNotFoundException();
    }

    public function getUserByName(string $name): ?User {
        foreach ($this->users as $user) {
            if ($user->getName() === $name) {
                return $user;
            }
        }
        throw new UserNotFoundException();
    }

    public function deleteUser(User $user): void {
        foreach ($this->users as $key => $u) {
            if ($u->getUserId() === $user->getUserId()) {
                unset($this->users[$key]);
                return;
            }
        }
        throw new UserNotFoundException();
    }

    /**
     * Get all users
     *
     * @return array<User>
     */
    public function getUsers(): array {
        return $this->users;
    }

    /**
     * Generates a new user id
     *
     * @return int
     */
    protected function generateUserId(): int {
        return count($this->users) + 1;
    }
}