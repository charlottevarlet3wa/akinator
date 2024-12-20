<?php

function redirect(string $path): void
{
    $basePath = '/3wa/php1/personality_project/akinator/';
    $url = $basePath . $path;
    header('Location: ' . $url);
    exit;
}
