<?php
echo "Hola Mundo";


/* if (true) {
    echo "Esto es un if.";
}

for ($i = 0; $i < 5; $i++) {
    echo "Esto es un bucle for. Iteración: $i";
}

$j = 0;
while ($j < 5) {
    echo "Esto es un bucle while. Iteración: $j";
    $j++;
} */

// Definición de un array
$frutas = array("Manzana", "Banana", "Cereza");

// Acceso a elementos del array
echo "La primera fruta es: " . $frutas[0];

// Recorrer un array con foreach
foreach ($frutas as $fruta) {
    echo "Fruta: " . $fruta;
}

// Array asociativo
$edades = array("Juan" => 25, "Ana" => 30, "Pedro" => 35);

// Acceso a elementos del array asociativo
echo "La edad de Juan es: " . $edades["Juan"];

// Recorrer un array asociativo con foreach
foreach ($edades as $nombre => $edad) {
    echo "$nombre tiene $edad años.";
}

// Declaración de una función simple
function saludar() {
    echo "Hola desde la función saludar!";
}

// Llamada a la función
saludar();

?>

