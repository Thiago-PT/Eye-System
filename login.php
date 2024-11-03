<?php
header('Content-Type: application/json');
$conexion = new mysqli('b4qhbwwqys2nhher1vul-mysql.services.clever-cloud.com', 'upvge9afjesbmmgv', 'BS2bxJNACO1XYEmWBqA0 ', 'b4qhbwwqys2nhher1vul');

if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida"]);
    exit();
}

$correo = $_POST['correo'];
$password = $_POST['password'];

$sql = "SELECT password FROM usuario WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();

if (password_verify($password, $hashed_password)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Correo o contraseña incorrectos"]);
}

$stmt->close();
$conexion->close();
?>
