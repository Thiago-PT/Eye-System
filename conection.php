<?php
// Configuración de la base de datos de Clever Cloud
$host = "b4qhbwwqys2nhher1vul-mysql.services.clever-cloud.com";
$dbname = "b4qhbwwqys2nhher1vul";
$user = "upvge9afjesbmmgv";
$password = "BS2bxJNACO1XYEmWBqA0";

// Habilitar CORS para permitir peticiones desde App Inventor
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener datos del POST
    $data = json_decode(file_get_contents("php://input"));
    
    if(!empty($data->correo) && !empty($data->password)) {
        // Preparar la consulta SQL (adaptada a tu estructura de tabla)
        $query = "SELECT id_usuario, correo FROM usuario WHERE correo = ? AND password = ?";
        $stmt = $conn->prepare($query);
        
        // Hash de la contraseña (recomendado usar password_hash en producción)
        $hashed_password = hash('sha256', $data->password);
        
        // Ejecutar la consulta
        $stmt->execute([$data->correo, $hashed_password]);
        
        if($stmt->rowCount() > 0) {
            // Usuario encontrado
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "id_usuario" => $row['id_usuario'],
                "correo" => $row['correo']
            ]);
        } else {
            // Usuario no encontrado
            echo json_encode([
                "status" => "error",
                "message" => "Correo o contraseña inválidos"
            ]);
        }
    } else {
        // Datos incompletos
        echo json_encode([
            "status" => "error",
            "message" => "Por favor proporcione correo y contraseña"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error de conexión: " . $e->getMessage()
    ]);
}
