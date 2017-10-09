<?php

namespace BiffBangPow\MessageBoard\Services;

use BiffBangPow\MessageBoard\Model\User;

class PasswordEncryptionService
{
    /**
     * @return string
     */
    private function createSalt()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 15; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @param string $password
     * @return User
     */
    public function encryptPassword(User $user, string $password)
    {
        $salt = $this->createSalt();

        $passwordConcatenated = $password.$salt;

        $hashedPassword = password_hash($passwordConcatenated, PASSWORD_BCRYPT);

        $user->setPassword($hashedPassword);
        $user->setSalt($salt);

        return $user;
    }
}