<?php
header('Content-Type: application/json');
$conexion = new mysqli('b4qhbwwqys2nhher1vul-mysql.services.clever-cloud.com', 'upvge9afjesbmmgv', 'BS2bxJNACO1XYEmWBqA0 ', 'b4qhbwwqys2nhher1vul');

if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida"]);
    exit();
}

$correo = $_POST['correo'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$sql = "INSERT INTO usuario (correo, password) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $correo, $password);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo crear el usuario"]);
}

$stmt->close();
$conexion->close();
?>
