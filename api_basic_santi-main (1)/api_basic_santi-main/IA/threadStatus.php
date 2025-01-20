<?php
function getThreadStatus($apiKey, $threadID, $runID, $timeout = 30) {
    $startTime = time();
    while (true) {
        // Inicializar cURL
        $ch = curl_init();

        // Configurar la URL de destino
        $url = "https://api.openai.com/v1/threads/$threadID/runs/$runID";
        curl_setopt($ch, CURLOPT_URL, $url);

        // Configurar las cabeceras HTTP
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey",
            "OpenAI-Beta: assistants=v2"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Configurar la opción para devolver la transferencia como una cadena
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la petición
        $response = curl_exec($ch);

        // Verificar si hubo errores en cURL
        if ($response === false) {
            $error = 'Error cURL: ' . curl_error($ch);
            curl_close($ch);
            return $error;
        }

        // Cerrar cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        $statusData = json_decode($response, true);

        // Registrar el estado para depuración
        error_log("Datos del estado de ejecución: " . print_r($statusData, true));

        // Verificar si hay un error en la respuesta de la API
        if (isset($statusData['error'])) {
            return 'Error de API: ' . $statusData['error']['message'];
        }

        // Verificar el estado de la ejecución
        if (isset($statusData['status']) && $statusData['status'] === 'completed') {
            // La ejecución ha completado exitosamente
            return $response;
        } elseif (isset($statusData['status']) && $statusData['status'] === 'failed') {
            // La ejecución ha fallado
            return 'La ejecución ha fallado.';
        } else {
            // Esperar 200 milisegundos antes de consultar nuevamente
            usleep(200000);
        }

        // Verificar el tiempo de espera
        if ((time() - $startTime) >= $timeout) {
            return 'Tiempo de espera agotado: La ejecución no se completó dentro del tiempo esperado.';
        }
    }
}
?>