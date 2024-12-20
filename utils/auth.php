<?php

function login(array $user): void
{
    // Stocke les informations pertinentes dans la session
    $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email']
    ];

    $_SESSION['is_authenticated'] = true;
    if ($user['role'] === 'admin') {
        $_SESSION['is_admin'] = true;
    }
}

function isAuthenticated(): bool
{
    return isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true;
}


function isAdmin(): bool
{
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function logout(): void
{
    // Supprime toutes les données de la session
    session_unset();
    session_destroy();
}

function getAuthenticatedUser(): ?array
{
    if (isAuthenticated()) {
        return $_SESSION['user'] ?? null;
    }
    return null;
}

function isPasswordStrong(string $password): bool
{
    $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d)(?=.*[\\W_])\\S{8,16}$/';
    return preg_match($passwordRegex, $password);
}

function isEmailValid(string $email): bool
{
    $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-z]{2,}$/';
    return preg_match($emailRegex, $email);
}
