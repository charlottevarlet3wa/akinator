<?php

session_start();

require_once './repository/userRepository.php';
require_once './utils/functions.php';
require_once './utils/auth.php';

if (
    !empty($_POST)
) {

    try {

        if (
            !isset($_POST['username']) || empty($_POST['username']) ||
            !isset($_POST['email']) || empty($_POST['email']) ||
            !isset($_POST['password']) || empty($_POST['password'])
        ) {
            throw new Exception("All fields are required.");
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!isEmailValid($email)) {
            throw new Exception('Invalid email format');
        }

        // Vérifier la force du mot de passe
        if (!isPasswordStrong($password)) {
            throw new Exception('Votre mot de passe doit contenir entre 8 et 16 lettres, avec au moins une majuscule, une minuscule, 1 chiffre et 1 caractère spécial.');
        }

        addUser($username, $email, $password);

        redirect('login.php');
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$template = './template/signup.phtml';
include_once './template/layout/main.phtml';
