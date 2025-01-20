<?php
include_once './database.php';

function addMessage($apiKey, $threadId, $message) {
    // Verificar que $threadId no esté vacío
    if (empty($threadId)) {
        return 'Error: El ID del hilo está vacío o mal formado.';
    }

    // Limpiar y codificar $threadId
    $threadId = trim($threadId);
    $threadIdEncoded = urlencode($threadId);

    // Detectar comando especial para obtener datos de la base de datos
    if (strpos($message, '/getUsers') !== false) {
        $database = new Database();
        $db = $database->getConnection();
        $sql = "SELECT * FROM users";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $message .= "\nUsuarios en la base de datos: " . json_encode($users);
    }

    // Detectar comando especial para añadir datos a la base de datos
    if (strpos($message, '/addUser') !== false) {
        // Extraer datos del mensaje
        preg_match('/\/addUser name=(.*) email=(.*)/', $message, $matches);
        $name = $matches[1];
        $email = $matches[2];

        // Hacer una solicitud POST a api.php para añadir el usuario
        $url = 'http://localhost/IA/api.php';
        $data = json_encode([
            'name' => $name,
            'email' => $email
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $message .= "\nRespuesta de la base de datos: " . $response;
    }

    // Inicializar cURL
    $ch = curl_init();

    // Configurar la URL de destino
    $url = "https://api.openai.com/v1/threads/$threadIdEncoded/messages";
    curl_setopt($ch, CURLOPT_URL, $url);

    // Configurar las cabeceras HTTP
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey",
        "OpenAI-Beta: assistants=v2"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Configurar la petición como POST
    curl_setopt($ch, CURLOPT_POST, true);

    // Configurar los datos POST
    $data = json_encode([
        "role" => "user",
        "content" => $message
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
    }

    // Cerrar cURL
    curl_close($ch);

    // Devolver la respuesta
    return $response;
}
?>
