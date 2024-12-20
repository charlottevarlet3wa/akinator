<?php

require_once './repository/database.php';

function addUser(string $username, string $email, string $clearPassword)
{
    $sql = 'INSERT INTO user (username, email, password) VALUES ( :username, :email, :password)';

    $query = getConnection()->prepare($sql);

    $query->execute([
        'username' => $username,
        'email' => $email,
        'password' => password_hash($clearPassword, PASSWORD_BCRYPT),
    ]);
}

function getUserByEmail(string $email): array|bool
{
    $sql = 'SELECT user.id, user.username, user.email, user.password, role.name AS role 
            FROM user 
            INNER JOIN role ON user.role_id = role.id 
            WHERE user.email = :email';

    $query = getConnection()->prepare($sql);

    $query->execute([
        'email' => $email
    ]);

    return $query->fetch();
}

function getUserById(string|int $id): ?array
{
    $sql = 'SELECT user.id, user.username, user.email, user.password, role.name AS role 
            FROM user 
            INNER JOIN role ON user.role_id = role.id 
            WHERE user.id = :id';

    $query = getConnection()->prepare($sql);

    $query->execute([
        'id' => $id
    ]);

    return $query->fetch();
}

function deleteUserById(string|int $userId)
{
    $sql = 'DELETE FROM user WHERE id = :userId';

    $query = getConnection()->prepare($sql);

    $query->execute([
        'userId' => $userId
    ]);
}

function updateUserById(int $userId, string $username, string $email): bool
{
    // Préparez la requête pour mettre à jour l'utilisateur
    $sql = 'UPDATE user SET username = :username, email = :email WHERE id = :id';

    $query = getConnection()->prepare($sql);

    // Exécutez la requête avec les paramètres
    return $query->execute([
        'id' => $userId,
        'username' => $username,
        'email' => $email
    ]);
}

function updatePassword(int $userId, string $currentPassword, string $newPassword): bool
{
    // Récupérer le mot de passe actuel depuis la base de données
    $sql = 'SELECT password FROM user WHERE id = :id';
    $query = getConnection()->prepare($sql);
    $query->execute(['id' => $userId]);
    $result = $query->fetch();
    if (!$result) {
        throw new Exception('User not found.');
    }
    $storedPassword = $result['password'];

    // Vérifiez que le mot de passe actuel correspond
    if (!password_verify($currentPassword, $storedPassword)) {
        throw new Exception('The current password is incorrect.');
    }

    // Mettre à jour avec le nouveau mot de passe
    $sql = 'UPDATE user SET password = :password WHERE id = :id';
    $query = getConnection()->prepare($sql);

    return $query->execute([
        'id' => $userId,
        'password' => password_hash($newPassword, PASSWORD_BCRYPT)
    ]);
}
