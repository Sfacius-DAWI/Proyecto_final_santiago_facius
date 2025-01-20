<?php
// Incluir todas las funciones del mismo directorio
include_once  './addMessage.php';
include_once  './addThread.php';
include_once  './getMessage.php';
include_once  './threadStatus.php';
include_once  './runThread.php';

$apiKey = "";
$threadID = null;
$response = '';

function getUsersFromDatabase() {
    $url = 'http://localhost/IA/api.php';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
    error_log("Mensaje recibido: " . htmlspecialchars($mensaje));

    // Obtener usuarios de la base de datos
    $users = getUsersFromDatabase();
    error_log("Usuarios obtenidos: " . json_encode($users));

    // Incluir los usuarios en el mensaje a la IA
    $mensaje .= "\nUsuarios en la base de datos: " . json_encode($users);

    // Verificar si el threadID está vacío
    $threadID = createThread($apiKey);
    $thread = json_decode($threadID);
    $thread_id = $thread->id;

    addMessage($apiKey, $thread_id, $mensaje);
    error_log("Mensaje enviado: " . htmlspecialchars($mensaje));

    $run_object = runThread($apiKey, $thread_id);
    $run = json_decode($run_object);
    $run_id = $run->id;

    $status = getThreadStatus($apiKey, $thread_id, $run_id);
    $response = getMessage($thread_id, $apiKey);

    // Decodificar la respuesta JSON a un array asociativo
    $data = json_decode($response, true);

    // Verificar si la decodificación fue exitosa
    if ($data === null) {
        echo "Error al decodificar la respuesta JSON.";
    } else {
        // Inicializar una variable para almacenar el mensaje del asistente
        $assistantMessage = '';

        // Verificar que 'data' existe y es un array
        if (isset($data['data']) && is_array($data['data'])) {
            // Recorrer los mensajes para encontrar el del asistente
            foreach ($data['data'] as $message) {
                if (isset($message['role']) && $message['role'] === 'assistant') {
                    if (isset($message['content'][0]['text']['value'])) {
                        $assistantMessage = $message['content'][0]['text']['value'];
                        break; // Salir del bucle después de encontrar el mensaje del asistente
                    }
                }
            }
        }

        // Mostrar el mensaje del asistente
        $responseArray = [
            'response' => $assistantMessage
        ];
        
        // Devolver la respuesta formateada como JSON
        header('Content-Type: application/json');
        echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
    }
} elseif (isset($_GET['getUsers'])) {
    // Obtener usuarios de la base de datos
    $users = getUsersFromDatabase();
    error_log("Usuarios obtenidos: " . json_encode($users));

    // Devolver los usuarios formateados como JSON
    header('Content-Type: application/json');
    echo json_encode($users, JSON_UNESCAPED_UNICODE);
}
?>