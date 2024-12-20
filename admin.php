<?php

require_once './repository/questionRepository.php';
require_once './repository/answerRepository.php';
require_once './repository/resultRepository.php';
require_once './utils/auth.php';
require_once './utils/functions.php';

session_start();

// if (!isAdmin()) {
//     redirect('');
//     exit;
// }

$error = '';
$success = '';

// Traitement des actions CRUD
if (!empty($_POST)) {
    try {
        // if (isset($_POST['create'])) {
        //     $content = trim($_POST['content']);
        //     $isFirst = isset($_POST['is_first']) ? 1 : 0;

        //     if (empty($content)) {
        //         throw new Exception("Le contenu de la question est obligatoire.");
        //     }

        //     $sql = 'INSERT INTO question (content, is_first) VALUES (:content, :is_first)';
        //     $query = getConnection()->prepare($sql);
        //     $query->execute(['content' => $content, 'is_first' => $isFirst]);

        //     $success = "Question ajoutée avec succès.";
        // }

        // if (isset($_POST['update'])) {
        //     $id = (int) $_POST['id'];
        //     $content = trim($_POST['content']);
        //     $isFirst = isset($_POST['is_first']) ? 1 : 0;

        //     if (empty($content)) {
        //         throw new Exception("Le contenu de la question est obligatoire.");
        //     }

        //     $sql = 'UPDATE question SET content = :content, is_first = :is_first WHERE id = :id';
        //     $query = getConnection()->prepare($sql);
        //     $query->execute(['content' => $content, 'is_first' => $isFirst, 'id' => $id]);

        //     $success = "Question mise à jour avec succès.";
        // }

        // if (isset($_POST['delete'])) {
        //     $id = (int) $_POST['id'];

        //     $sql = 'DELETE FROM question WHERE id = :id';
        //     $query = getConnection()->prepare($sql);
        //     $query->execute(['id' => $id]);

        //     $success = "Question supprimée avec succès.";
        // }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$questions = getQuestionsAndAnswers();
$results = getResults();

var_dump($results);

// var_dump($questions[0]);

$template = './template/admin.phtml';
include_once './template/layout/main.phtml';
