<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$conexion = new mysqli('b4qhbwwqys2nhher1vul-mysql.services.clever-cloud.com', 'upvge9afjesbmmgv', 'BS2bxJNACO1XYEmWBqA0', 'b4qhbwwqys2nhher1vul');

// Verificar la conexión
if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conexion->connect_error]);
    exit();
}

// Recibir datos del formulario
$correo = $_POST['correo'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Preparar la consulta SQL
$sql = "INSERT INTO admins (correo, password) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $correo, $password);

// Ejecutar la consulta y manejar el resultado
if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo crear el usuario: " . $stmt->error]);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conexion->close();
?>
