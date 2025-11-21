<?php
	session_start();
	include 'includes/conn.php';
	if (isset($_POST['login'])) {
    // Saneamiento básico de entrada
    $dni = trim($_POST['dni'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($dni) || empty($password)) {
        $_SESSION['error'] = 'Ingrese credenciales válidas';
        header('Location: index.php');
        exit();
    }

    // Consulta preparada para evitar SQL injection
    $stmt = $conn->prepare("SELECT dni, password FROM voters WHERE dni = ?");
    if (!$stmt) {
        $_SESSION['error'] = 'Error en el servidor';
        header('Location: index.php');
        exit();
    }

    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Mensaje genérico para no revelar existencia del votante
        $_SESSION['error'] = 'Credenciales incorrectas';
    } else {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Inicio de sesión seguro
            session_regenerate_id(true);
            $_SESSION['voter'] = $row['dni'];
        } else {
            $_SESSION['error'] = 'Credenciales incorrectas';
        }
    }

    $stmt->close();
    header('Location: index.php');
    exit();
} else {
    $_SESSION['error'] = 'Por favor ingrese las credenciales';
    header('Location: index.php');
    exit();
}