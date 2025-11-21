<?php
// Iniciar sesión si aún no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Importar conexión a la base de datos
require_once __DIR__ . '/conn.php';

// Validación de sesión
if (!isset($_SESSION['voter'])) {
    header('Location: index.php');
    exit;
}

$dni = $_SESSION['voter'];

// Asegurar que la conexión existe
if (!isset($conn) || $conn->connect_error) {
    error_log("Error de conexión a la base de datos: " . ($conn->connect_error ?? 'Conexión no inicializada'));
    header('Location: error.php');
    exit;
}

// Consulta segura
$stmt = $conn->prepare("SELECT * FROM voters WHERE dni = ?");
if (!$stmt) {
    error_log("Error preparando consulta SQL: " . $conn->error);
    header('Location: error.php');
    exit;
}

$stmt->bind_param("s", $dni);
$stmt->execute();

$result = $stmt->get_result();
$voter = $result->fetch_assoc();

// Validar existencia del votante
if (!$voter) {
    error_log("Intento de acceso con DNI inexistente: $dni");
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
