<?php
session_start();

require_once './repository/questionRepository.php';
require_once './repository/answerRepository.php';
require_once './utils/functions.php';

var_dump($_POST);

// Vérifie si le script est appelé suite à la soumission du formulaire quiz.phtml
if (!empty($_POST)) {
    // Si l'utilisateur a répondu (?)
    if (!empty($_POST['answer'])) {
        $answerId = (int)$_POST['answer'];
        $answer = getAnswerById($answerId);

        // Si la réponse mène à un resultat, aller sur la page result.php
        if (isset($answer['result_id'])) {
            $_SESSION['result_id'] = $answer['result_id'];
            redirect('result.php');
            exit;
        }

        $questionId = $answer['next_question_id'];
        $question = getQuestionById($questionId);
    }
} else {
    // Charger la première question si l'on n'a pas encore commencé le quiz
    $question = getFirstQuestion();
    $questionId = (int) $question['id'];
}

$options = getAnswersFromQuestionId($questionId);

// Définition du template
$template = './template/quiz.phtml';
include_once './template/layout/main.phtml';
