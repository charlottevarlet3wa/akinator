<?php

session_start();

require_once './repository/userRepository.php';
require_once './utils/functions.php';
require_once './utils/auth.php';

if (!isAuthenticated()) {
    redirect('login.php');
    exit;
}

$user = getAuthenticatedUser();
$error = '';

if (!empty($_POST)) {
    try {
        // Premier formulaire : changer le nom d'utilisateur et l'email
        if (isset($_POST['username']) && isset($_POST['email'])) {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);

            if (empty($username) || empty($email)) {
                throw new Exception("Le nom d'utilisateur et l'email sont obligatoires.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("L'email n'est pas valide.");
            }

            updateUserById($user['id'], $username, $email);
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            redirect('account.php');
            exit;
        }

        // Deuxième formulaire : changer le mot de passe
        if (isset($_POST['current_password']) && isset($_POST['new_password'])) {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];

            if (empty($currentPassword) || empty($newPassword)) {
                throw new Exception("Tous les champs sont obligatoires pour changer le mot de passe.");
            }

            // Vérifier la force du mot de passe
            if (!isPasswordStrong($newPassword)) {
                throw new Exception('Votre mot de passe doit contenir entre 8 et 16 lettres, avec au moins une majuscule, une minuscule, 1 chiffre et 1 caractère spécial.');
            }

            updatePassword($user['id'], $currentPassword, $newPassword);
            redirect('account.php');
            exit;
        }

        // Formulaire de suppression de compte
        if (isset($_POST['delete_account'])) {
            deleteUserById($user['id']);
            logout();
            redirect('index.php');
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$template = './template/account.phtml';
include_once './template/layout/main.phtml';
