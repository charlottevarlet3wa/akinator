<?php

session_start();

require_once './repository/resultRepository.php';
require_once './utils/functions.php';



if (!isset($_SESSION['result_id'])) {
    redirect('');
}

$resultId = $_SESSION['result_id'];
$result = getResultById($resultId);

// Définition du template
$template = './template/result.phtml';
include_once './template/layout/main.phtml';
