<?php
// Iniciar sesión solo si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Importar conexión a la base de datos
require_once __DIR__ . '/conn.php';

// Validación básica de sesión
if (!isset($_SESSION['admin']) || trim($_SESSION['admin']) === '') {
    header('Location: index.php');
    exit;
}

$adminId = $_SESSION['admin'];

// Verificar conexión a la BD
if (!isset($conn) || $conn->connect_error) {
    error_log("Error de conexión en admin.php: " . ($conn->connect_error ?? 'Conexión no inicializada'));
    header('Location: error.php');
    exit;
}

// Consulta segura con sentencia preparada
$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
if (!$stmt) {
    error_log("Error preparando consulta (admin): " . $conn->error);
    header('Location: error.php');
    exit;
}

$stmt->bind_param("i", $adminId); // id = entero (i)
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Validar existencia del administrador
if (!$user) {
    error_log("Administrador no encontrado: ID = $adminId");
    session_destroy();
    header('Location: index.php');
    exit;
}

?>
