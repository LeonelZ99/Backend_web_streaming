<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

// Conecta a la base de datos (asegúrate de tener la conexión a la base de datos configurada)
$host = "localhost";
$usuario = "root";
$password = "";
$bd = "api_streaming";
$conexion = new mysqli($host, $usuario, $password, $bd);

if ($conexion->connect_error) {
    die("La conexión a la base de datos ha fallado: " . $conexion->connect_error);
}

// Recibe datos del evento desde la solicitud POST en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verifica si los datos se recibieron correctamente
if (empty($data['name']) || empty($data['description']) || empty($data['imageUrl'])) {
    echo json_encode(array('message' => 'Error: Todos los campos son obligatorios.'));
    exit;
}

// Sanitiza y escapa los datos para evitar inyecciones SQL
$nombre_evento = $conexion->real_escape_string($data['nombre_evento']);
$tipo_deporte = $conexion->real_escape_string($data['tipo_deporte']);
$lugar_evento = $conexion->real_escape_string($data['lugar_evento']);
$fecha_evento = $conexion->real_escape_string($data['fecha_evento']);
$img_evento = $conexion->real_escape_string($data['img_evento']);
$horario_evento = $conexion->real_escape_string($data['horario_evento']);
$url_transimision = $conexion->real_escape_string($data['url_transmision']);


// Consulta para insertar un nuevo evento
$sql = "INSERT INTO eventodeporte (nombre_evento, tipo_deporte,lugar_evento,fecha_evento, img_evento, horario_evento, url_transimision) VALUES ('$nombre_evento', '$tipo_deporte', '$lugar_evento','$fecha_evento','$img_evento','$horario_evento','$url_transmision')";
// Ajusta la consulta según la estructura real de tu tabla y los campos necesarios.

if ($conexion->query($sql) === TRUE) {
    echo json_encode(array('message' => 'Evento agregado exitosamente'));
} else {
    echo json_encode(array('message' => 'Error al agregar el evento: ' . $conexion->error));
}

// Cierra la conexión a la base de datos
$conexion->close();
?>
