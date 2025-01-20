# IA Ejecutando Funciones del Código

Este proyecto tiene como objetivo comprobar si una inteligencia artificial (IA) puede interactuar directamente con las funciones de un código. En particular, se busca explorar la posibilidad de que la IA llame a una función que realiza una petición a una base de datos, interprete el resultado obtenido y devuelva los datos necesarios de manera precisa.

## Objetivos Principales

1. **Integración de IA y funciones del código**: Evaluar si la IA puede entender y llamar correctamente a funciones definidas en el código.
2. **Interacción con bases de datos**: Verificar si la función que llama la IA puede conectarse a una base de datos MySQL, realizar consultas y procesar los resultados.
3. **Interpretación y respuesta**: Analizar si la IA puede interpretar los datos obtenidos de la base de datos para devolver respuestas ajustadas a las necesidades del usuario.

## Casos de Uso

1. **Automatización de consultas**: Una IA que interpreta comandos del usuario, llama a funciones específicas en el código y devuelve resultados de manera contextual.
2. **Procesamiento avanzado**: Usar la IA para procesar y formatear los datos obtenidos antes de devolverlos al usuario.

## Estructura del Proyecto

- **Backend**: Implementación de las funciones que realiza peticiones a una base de datos utilizando PHP.
- **Integración con IA**: Uso de una API de inteligencia artificial (como OpenAI GPT-4) para llamar a las funciones del backend.
- **Base de Datos**: Sistema de almacenamiento de datos gestionado con MySQL.
- **Respuesta de la IA**: La IA recibe los resultados de las funciones y devuelve respuestas interpretadas.

## Tecnologías Utilizadas

- **PHP**: Para implementar el backend y la lógica de las funciones.
- **phpAdmin**: Base de datos relacional para almacenar y consultar los datos.
- **API de OpenAI**: Para la interacción y el procesamiento de datos por parte de la IA.
- **JSON**: Formato para intercambiar datos entre las funciones y la IA.


## Posibilidades Futuras

Este proyecto puede evolucionar hacia:
- Automatización de tareas complejas en tiempo real.
- Expansión del modelo para integrar múltiples fuentes de datos.
- Creación de asistentes inteligentes capaces de realizar análisis más profundos.

---

# Funcionamineto Parte por Parte 

# API de Interacción con OpenAI

Este proyecto proporciona una serie de scripts PHP para interactuar con la API de OpenAI. Los scripts permiten crear hilos, enviar mensajes, obtener el estado de los hilos y gestionar usuarios en una base de datos.

## Archivos

### `central.php`

Este archivo incluye todas las funciones necesarias y define la lógica principal para interactuar con la API de OpenAI.

- **Incluir Funciones**: Incluye todas las funciones necesarias desde otros archivos.
- **Variables Globales**: Define variables globales como `$apiKey`, `$threadID`, y `$response`.
- **Función `getUsersFromDatabase`**: Realiza una solicitud HTTP GET para obtener los usuarios de la base de datos.

### `addMessage.php`

Este archivo define la función `addMessage` que envía un mensaje a un hilo específico en la API de OpenAI. También maneja comandos especiales para obtener y añadir usuarios a la base de datos.

#### Funciones Especiales

- **Comando `/getUsers`**:
  - Extrae el comando del mensaje.
  - Realiza una consulta a la base de datos para obtener todos los usuarios.
  - Añade los resultados al mensaje original.

- **Comando `/addUser`**:
  - Extrae el comando del mensaje.
  - Extrae el nombre y el correo electrónico del mensaje.
  - Realiza una solicitud POST a `api.php` para añadir el usuario a la base de datos.
  - Añade la respuesta de la base de datos al mensaje original.

### `addThread.php`

Este archivo define la función `createThread` que crea un nuevo hilo en la API de OpenAI.

- **Función `createThread`**:
  - Realiza una solicitud POST a la API de OpenAI para crear un nuevo hilo.
  - Devuelve la respuesta de la API.

### `database.php`

Este archivo define la clase `Database` que maneja la conexión a la base de datos.

- **Clase `Database`**:
  - Define los parámetros de conexión a la base de datos.
  - Proporciona el método `getConnection` para obtener una conexión a la base de datos.

### `getMessage.php`

Este archivo define la función `getMessage` que obtiene los mensajes de un hilo específico en la API de OpenAI.

- **Función `getMessage`**:
  - Realiza una solicitud GET a la API de OpenAI para obtener los mensajes de un hilo.
  - Devuelve la respuesta de la API.

### `runThread.php`

Este archivo define la función `runThread` que ejecuta un hilo en la API de OpenAI.

- **Función `runThread`**:
  - Realiza una solicitud POST a la API de OpenAI para ejecutar un hilo.
  - Devuelve la respuesta de la API.

### `threadStatus.php`

Este archivo define la función `getThreadStatus` que obtiene el estado de ejecución de un hilo en la API de OpenAI.

- **Función `getThreadStatus`**:
  - Realiza solicitudes periódicas a la API de OpenAI para obtener el estado de ejecución de un hilo.
  - Devuelve la respuesta de la API cuando el estado es `completed` o `failed`.


## Porque se crea primero el hilo

El hilo se crea primero debio a que gpt lo utiliza para identificar la conversación y a su vez guardar el contexto entre los mensajes.
El flujo de trabajo se basa en crear un hilo, añadir los mensajes y después procesar toda la conversación, es importante monitorizar la respuesta 
debido a que si devuelves una respuesta antes que la IA responda tan se devolvera de forma autoamitca al pregunta del usuario llevando a un error.
Esta forma de trabajar te permite prealmacenar una serie de oredenes en la conversación antes de que el usuario inicie la conversación como por ejemplo ofrecer 
ejemplos de como llevar a cabo una tarea para que la IA la pueda desarollar de mejor manera.

## Que tengo que hacer si quiero llamar a la base de datos ?

Es tan senillo como poner /getUsers esto cargara toda la base de datos en la conversación permitiendote hacer cualquier pregunta 
independientemente de lo extraña que pueda ser, compruebe usted mismo que si hace /getUsers y despues pregunta cual es la media 
de letras de los nombres de todas las peronas en esta base de datos la IA respondera 11,34 cuando esto es un comportamiento 
que usted como programador no ha tenido que preparar.

```
http://localhost/IA/central.php?mensaje=GET http://localhost/bases_de_datos/api.php

aqui le dejo el curl de postman

curl --location 'http://localhost/IA/central.php?mensaje=GET%20http%3A%2F%2Flocalhost%2Fbases_de_datos%2Fapi.php'

```

y para hacer un /addUser se lanza el comando junto con el endponit al que quiero llamar esto quedaria de la siguiente manera

```
http://localhost/IA/central.php?mensaje=/addUser name=Esto_se_ha_metido_con_IA Doe email=IA_Santi@example.com

aqui le dejo el curl de postman

curl --location 'http://localhost/IA/central.php?mensaje=%2FaddUser%20name%3DEsto_se_ha_metido_con_IA%20Doe%20email%3DIA_Santi%40example.com'

```

# importante 

Es una version preliminar despues de cargar la base de datos puedes hacer otra petición sin problema ninguno pero hoy por hoy tiene que 
ser un mensaje despues de otro, no es correcto hacer el /get y la pregunta a la vez esto debido a que es una version simplificada.
Para hacer cualquier pregunta se cambia la información de mensaje no se para el servidor o el contexto de la IA se perdera. 


