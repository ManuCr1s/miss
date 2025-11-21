<?php
	session_start();
	include 'includes/conn.php';

	if(isset($_POST['login'])){

		$username = trim($_POST['username'] ?? '');
		$password = $_POST['password'] ?? '';

		if (empty($username) || empty($password)) {
			$_SESSION['error'] = 'Ingrese credenciales válidas';
			header('Location: index.php');
			exit();
		}
		$stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
		if (!$stmt) {
			// Error de preparación
			$_SESSION['error'] = 'Error en el servidor';
			header('Location: index.php');
			exit();
		}
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows === 0) {
			// Mensaje genérico para no revelar existencia de usuario
			$_SESSION['error'] = 'Credenciales incorrectas';
		} else {
			$row = $result->fetch_assoc();
			// Verifica la contraseña usando password_verify
			if (password_verify($password, $row['password'])) {
				// Inicio de sesión seguro
				session_regenerate_id(true);
				$_SESSION['admin'] = $row['id'];
			} else {
				$_SESSION['error'] = 'Credenciales incorrectas';
			}
		}
		$stmt->close();
		header('Location: index.php');
		exit();
	}
	else{
		$_SESSION['error'] = 'Por favor ingrese las credenciales';
		header('Location: index.php');
		exit();
	}
