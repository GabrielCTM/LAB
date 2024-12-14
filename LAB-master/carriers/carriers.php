<?php

$baseUrl = 'http://localhost:8080/api/carriers'; 
$wsKey = 'QS9EUMM54DL74P7YIHFIS6V53S297V7I'; 

// Configuración de la solicitud
$options = [
    'http' => [
        'header' => "Authorization: Basic " . base64_encode($wsKey . ':'),
        'method'  => 'GET',
    ]
];

$context = stream_context_create($options);

// Resultadoss

$response = file_get_contents($baseUrl, false, $context);


if ($response === FALSE) {
    die('Error al acceder al Endpoint Carriers');
}

$data = json_decode($response, true);
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>

