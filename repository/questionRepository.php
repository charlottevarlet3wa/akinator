<?php

require_once './repository/database.php';

function getQuestionById(int $questionId): array|bool
{
    $sql = 'SELECT * FROM question WHERE id = :questionId';

    $query = getConnection()->prepare($sql);

    $query->execute(['questionId' => $questionId]);

    return $query->fetch();
}

function getFirstQuestion(): array|bool
{
    $sql = 'SELECT * FROM question WHERE is_first = 1 LIMIT 1';

    $query = getConnection()->prepare($sql);

    $query->execute();

    return  $query->fetch();
}

function getNextQuestionFromAnswerId(int $answerId): array|bool
{
    $sql = '
        SELECT q.id, q.content 
        FROM answer a
        INNER JOIN question q ON a.next_question_id = q.id
        WHERE a.id = :answerId
    ';

    $query = getConnection()->prepare($sql);

    $query->execute(['answerId' => $answerId]);

    return $query->fetch();
}

// CRUD
function getQuestionsAndAnswers(): array|bool
{
    $sql = '
    SELECT question.id AS question_id, question.content AS question_content, question.is_first AS question_is_first,
           answer.id AS answer_id, answer.content AS answer_content, answer.next_question_id AS answer_next_question_id, answer.result_id AS answer_result_id
    FROM question
    LEFT JOIN answer ON question.id = answer.question_id
    ORDER BY question.id, answer.id
';

    $query = getConnection()->query($sql);
    $results = $query->fetchAll();

    // Regrouper les réponses par question
    $questionsWithAnswers = [];
    foreach ($results as $row) {
        $questionId = $row['question_id'];

        // Si la question n'est pas encore ajoutée, on l'ajoute
        if (!isset($questionsWithAnswers[$questionId])) {
            $questionsWithAnswers[$questionId] = [
                'id' => $row['question_id'],
                'content' => $row['question_content'],
                'is_first' => $row['question_is_first'],
                'answers' => []
            ];
        }

        // Ajouter les réponses dans le tableau "answers" de la question
        if (!empty($row['answer_id'])) {
            $questionsWithAnswers[$questionId]['answers'][] = [
                'id' => $row['answer_id'],
                'content' => $row['answer_content'],
                'next_question_id' => $row['answer_next_question_id'],
                'result_id' => $row['answer_result_id'],
            ];
        }
    }

    // Re-indexation (optionnelle si vous préférez un tableau indexé numériquement)
    $questionsWithAnswers = array_values($questionsWithAnswers);
    return $questionsWithAnswers;
}
