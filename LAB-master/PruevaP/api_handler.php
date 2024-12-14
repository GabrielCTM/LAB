<?php

// URL base de la API de PrestaShop
define('API_URL', 'http://localhost:8080/api/customers?output_format=JSON');

// Función para obtener todos los clientes
function getCustomers() {
    $curl = curl_init();

    // Configuración de cURL
    curl_setopt_array($curl, array(
        CURLOPT_URL => API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic TTdIMUg3ODk0Nk1VMktGSThLODNZRUJONTNDNTIxN0Q6',
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);

    // Verifica si hay errores
    if(curl_errno($curl)){
        echo 'Error cURL: ' . curl_error($curl);
    }

    curl_close($curl);

    return json_decode($response, true); // Decodifica la respuesta JSON
}
?>
