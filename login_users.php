<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conexion = new mysqli('b4qhbwwqys2nhher1vul-mysql.services.clever-cloud.com', 'upvge9afjesbmmgv', 'BS2bxJNACO1XYEmWBqA0', 'b4qhbwwqys2nhher1vul');

// Verificar la conexión
if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida"]);
    exit();
}

// Recibir datos del formulario
$email = $_POST['email'];
$contraseña = $_POST['contraseña'];

// Preparar la consulta SQL
$sql = "SELECT contraseña FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($hashed_contraseña);
$stmt->fetch();

// Verificar la contraseña
if (password_verify($contraseña, $hashed_contraseña)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Correo o contraseña incorrectos"]);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conexion->close();
?>
