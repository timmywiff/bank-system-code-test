<?php

namespace BankSystem\Models;

class User {
    private readonly int $userId;
    private string $name;
    private string $email;
    private array $accounts = [];

    private function __construct(int $userId, string $name, string $email) {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Create a new user
     *
     * @param int $userId
     * @param string $name
     * @param string $email
     * @return User
     */
    public static function create(int $userId, string $name, string $email): User {
        return new User($userId, $name, $email);
    }

    /**
     * Add a new account to the user
     * 
     * @param BankAccount $account
     * @return void
     */
    public function addAccount(BankAccount $account): void {
        $this->accounts[] = $account;
    }

    /**
     * Get all accounts of the user
     * 
     * @return array<BankAccount>
     */
    public function getAccounts(): array {
        return $this->accounts;
    }

    /**
     * Get the user id
     * 
     * @return int
     */
    public function getUserId(): int {
        return $this->userId;
    }

    /**
     * Get the name of the user
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set the name of the user
     * 
     * @param string $name
     * @return void
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get the email of the user
     * 
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Set the email of the user
     * 
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }
}
