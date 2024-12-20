<?php

require_once './repository/database.php';

function getAnswerById(int $answerId): array|bool
{
    $sql = 'SELECT * FROM answer WHERE id = :answerId';

    $query = getConnection()->prepare($sql);

    $query->execute(['answerId' => $answerId]);

    return $query->fetch();
}

function getAnswersFromQuestionId(int $questionId): array|bool
{
    $sql = 'SELECT * FROM answer WHERE question_id = :questionId';

    $query = getConnection()->prepare($sql);

    $query->execute(['questionId' => $questionId]);

    return $query->fetchAll();
}
