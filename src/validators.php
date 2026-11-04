<?php

namespace App\Utils;

class Validators
{
    // Fonction 1 : isValidEmail
    public static function isValidEmail(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Fonction 2 : isValidPassword
    public static function isValidPassword(?string $password): array
    {
        $errors = [];
        if (empty($password)) {
            return ['valid' => false, 'errors' => ['Password is required']];
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number.';
        }
        if (!preg_match('/[!@#$%^&*]/', $password)) {
            $errors[] = 'Password must contain at least one special character (!@#$%^&*).';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    // Fonction 3 : isValidAge
    public static function isValidAge(mixed $age): bool
    {
        if (!is_int($age) && !is_float($age)) {
            return false;
        }
        // Vérifie si c'est un entier (pas de décimaux type 25.5)
        if (is_float($age) && floor($age) !== $age) {
            return false;
        }
        return $age >= 0 && $age <= 150;
    }
}