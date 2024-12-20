<?php

require_once './repository/database.php';

function getResultById(int $resultId): array|bool
{
    $sql = 'SELECT * FROM result WHERE id = :resultId';

    $query = getConnection()->prepare($sql);

    $query->execute(['resultId' => $resultId]);

    return $query->fetch();
}
