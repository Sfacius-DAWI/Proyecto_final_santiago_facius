<?php

function createThread($apiKey) {
    $url = 'https://api.openai.com/v1/threads';

    $options = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n" .
                         "Authorization: Bearer $apiKey\r\n" .
                         "OpenAI-Beta: assistants=v2\r\n",
            'method'  => 'POST',
        ),
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        // Handle error
        return null;
    }

    return $result;
}

