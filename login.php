<?php

session_start();

require_once './repository/userRepository.php';
require_once './utils/functions.php';
require_once './utils/auth.php';

if (isAuthenticated()) {
    redirect('account.php');
    exit;
}

if (!empty($_POST)) {
    try {
        if (
            !isset($_POST['email']) || empty($_POST['email']) ||
            !isset($_POST['password']) || empty($_POST['password'])
        ) {
            throw new Exception("All fields are required.");
        }

        $user = getUserByEmail($_POST['email']);

        if (!$user || !password_verify($_POST['password'], $user['password'])) {
            throw new Exception("Invalid credentials.");
        }

        login($user);

        redirect('account.php');
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$template = './template/login.phtml';
include_once './template/layout/main.phtml';
