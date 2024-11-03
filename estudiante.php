<?php
header('Content-Type: application/json');
$conexion = new mysqli('b4qhbwwqys2nhher1vul-mysql.services.clever-cloud.com', 'upvge9afjesbmmgv', 'BS2bxJNACO1XYEmWBqA0', 'b4qhbwwqys2nhher1vul');

if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida"]);
    exit();
}

$codigo_est = $_POST['codigo_est'];
$apellidos = $_POST['apellidos'];
$nombres = $_POST['nombres'];
$grupo = $_POST['grupo'];
$jornada = $_POST['jornada'];

$sql = "INSERT INTO estudiante (codigo_est, apellidos, nombres, grupo, jornada) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssss", $codigo_est, $apellidos, $nombres, $grupo, $jornada);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo guardar el estudiante"]);
}

$stmt->close();
$conexion->close();
?>
