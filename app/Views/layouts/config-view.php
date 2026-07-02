<?php

$baseUrl = '/atendelab/public/';

function url(string $path): string
{
    global $baseUrl;
    return $baseUrl . ltrim($path, '/');
}
