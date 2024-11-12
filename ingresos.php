<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conn = mysqli_connect(
    'b4qhbwwqys2nhher1vul-mysql.services.clever-cloud.com',
    'upvge9afjesbmmgv',
    'BS2bxJNACO1XYEmWBqA0',
    'b4qhbwwqys2nhher1vul'
);

// Obtener parámetros
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$grupo = $_POST['grupo'] ?? '';
$jornada = $_POST['jornada'] ?? '';
$busqueda = $_POST['busqueda'] ?? '';

// Construir consulta
$sql = "SELECT i.id_ingreso, i.codigo_est, 
        CONCAT(e.apellidos, ', ', e.nombres) as estudiante,
        e.grupo, e.jornada, i.fecha, i.hora
        FROM ingreso i
        JOIN estudiante e ON i.codigo_est = e.codigo_est
        WHERE 1=1";

$params = array();

if ($fecha_inicio) {
    $sql .= " AND i.fecha >= ?";
    $params[] = $fecha_inicio;
}

if ($fecha_fin) {
    $sql .= " AND i.fecha <= ?";
    $params[] = $fecha_fin;
}

if ($grupo) {
    $sql .= " AND e.grupo LIKE ?";
    $params[] = "%$grupo%";
}

if ($jornada != 'Todas') {
    $sql .= " AND e.jornada = ?";
    $params[] = $jornada;
}

if ($busqueda) {
    $sql .= " AND (e.codigo_est LIKE ? OR e.nombres LIKE ? OR e.apellidos LIKE ?)";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
}

$sql .= " ORDER BY i.fecha DESC, i.hora DESC";

// Preparar y ejecutar consulta
$stmt = mysqli_prepare($conn, $sql);

if ($params) {
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Formatear resultado
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

mysqli_close($conn);
?>
