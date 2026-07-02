<?php

// Garantir que a sessão esteja iniciada para uso em views (header/footer)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = '/atendelab/public/';

function url(string $path): string
{
    global $baseUrl;
    return $baseUrl . ltrim($path, '/');
}
