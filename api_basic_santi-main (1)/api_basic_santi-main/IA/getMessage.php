<?php
function getMessage($thread, $apiKey) {
    // Inicializar cURL
    $ch = curl_init();

    // Configurar la URL de destino
    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/threads/{$thread}/messages");

    // Configurar las cabeceras HTTP
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey", // Usar la variable correcta
        "OpenAI-Beta: assistants=v2"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Configurar la opción para devolver la transferencia como una cadena
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Ejecutar la petición
    $response = curl_exec($ch);

    // Verificar si hubo errores
    if (curl_errno($ch)) {
        $error = 'Error:' . curl_error($ch);
        curl_close($ch); // Cerrar cURL antes de devolver el error
        return $error;
    } else {
        // Cerrar cURL
        curl_close($ch);
        // Devolver la respuesta
        return $response;
    }
}
?>