<?php

function runThread($key, $threadId) {
    // Inicializar cURL
    $ch = curl_init();

    // Configurar la URL de destino
    $url = "https://api.openai.com/v1/threads/{$threadId}/runs";
    curl_setopt($ch, CURLOPT_URL, $url);

    // Configurar las cabeceras HTTP
    $headers = [
        "Authorization: Bearer $key",
        "Content-Type: application/json",
        "OpenAI-Beta: assistants=v2"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Configurar la petición como POST
    curl_setopt($ch, CURLOPT_POST, true);

    // Configurar los datos POST
    $data = json_encode([
        "assistant_id" => "asst_BFZJZs3f46yU3TnWwwq4B2z8"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Configurar la opción para devolver la transferencia como una cadena
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la petición
    $response = curl_exec($ch);

    // Verificar si hubo errores
    if ($response === false) {
        $error = 'Error: ' . curl_error($ch);
        curl_close($ch);
        return $error;
    } else {
        // Devolver la respuesta
        curl_close($ch);
        return $response;
    }
}

?>