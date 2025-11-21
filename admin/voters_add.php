<?php
	include 'includes/conn.php';
	session_start();
	header('Content-Type: application/json');
	$ip = $_SERVER['REMOTE_ADDR'];
	$limitKey = 'rate_limit_' . $ip;
	if (!isset($_SESSION[$limitKey])) {
		$_SESSION[$limitKey] = ['count' => 0, 'time' => time()];
	}
	if (time() - $_SESSION[$limitKey]['time'] < 30) {
			$_SESSION[$limitKey]['count']++;
			if ($_SESSION[$limitKey]['count'] > 5) {
				$response['status'] = 429;
				$response['message'] = 'Demasiadas solicitudes. Intente más tarde.';
				echo json_encode($response);
				exit();
			}
	} else {
		$_SESSION[$limitKey] = ['count' => 1, 'time' => time()];
	}
	if (!isset($_POST['dni']) || trim($_POST['dni']) === '') {
		$response['status'] = 2;
		$response['message'] = 'Ingrese el DNI.';
		echo json_encode($response);
		exit();
	}
	$dni = trim($_POST['dni']);
	if (!preg_match('/^[0-9]{8}$/', $dni)) {
		$response['status'] = 3;
		$response['message'] = 'El DNI debe tener 8 dígitos numéricos.';
		echo json_encode($response);
		exit();
	}
	$stmt = $conn->prepare("SELECT id FROM voters WHERE dni = ?");
	$stmt->bind_param("s", $dni);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$response['status'] = 4;
		$response['message'] = 'El DNI ya está registrado.';
		$stmt->close();
		echo json_encode($response);
		exit();
	}
	$stmt->close();
	$token = getenv('FACTILIZA_API_TOKEN');

	if (!$token) {
		$response['status'] = 500;
		$response['message'] = "Error interno: token no configurado.";
		echo json_encode($response);
		exit();
	}
	
	$endpoint = "https://api.factiliza.com/v1/dni/info/" . urlencode($dni);

	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL            => $endpoint,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 20,
		CURLOPT_FAILONERROR    => false,
		CURLOPT_HTTPHEADER     => [
			"Authorization: Bearer $token",
			"Accept: application/json"
		],
	]);

	$result = curl_exec($curl);
	$curlErr = curl_error($curl);
	$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	if ($curlErr) {
		$response['status'] = 500;
		$response['message'] = 'Error de conexión con la API externa.';
		echo json_encode($response);
		exit();
	}

	if ($httpStatus !== 200) {
		$response['status'] = $httpStatus;
		$response['message'] = "Error en la API externa. Código HTTP: $httpStatus";
		echo json_encode($response);
		exit();
	}
	$apiresponse = json_decode($result, true);
	$response = [
		'status' => 1,
		'message' => 'Consulta exitosa',
		'apiresponse' => $apiresponse
	];

	echo json_encode($response);
	exit();

	



/*
	$response = ['status' => '0', 'message' => 'Algo salió mal'];
	if(isset($_POST['dni'])){
		$dni= $_POST['dni'];
		$check = $conn->query("SELECT * FROM voters WHERE dni = '$dni'");
		if($check->num_rows > 0){
			$response['message'] = 'El DNI ya está registrado.';
		}else{
			$key = $_POST['dni'];
			/* $token = 'sk_8431.V90eyloQeOKiJJGJMikpimCfpKcd9jWD'; 
			$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzOTg4MiIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.LnnxT69Mll6bmJN0vcj-rntearWB4-dEo0vd_pvOppU';
			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => "https://api.factiliza.com/v1/dni/info/".$key,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => [
					"Authorization: Bearer $token"
				],
			]);
			$apiresponse = curl_exec($curl);
			curl_close($curl);
			$apiresponse = json_decode($apiresponse, true);
			$response = ['status' => '1', 'apiresponse' =>$apiresponse];
		}
	}else{
		$response ['status'] = '2';
		$response['message'] = 'Ingrese datos del dni';
	}
	if(isset($_POST['dni'], $_POST['firstname'], $_POST['lastname'], $_POST['password'])){
		$dni= $_POST['dni'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		// generate voters id
		$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voter = substr(str_shuffle($set), 0, 15);

		// check if DNI exists
		$check = $conn->query("SELECT * FROM voters WHERE dni = '$dni'");
		if($check->num_rows > 0){
			$response['message'] = 'El DNI ya está registrado.';
		} else {
			$sql = "INSERT INTO voters (voters_id,dni, password, firstname, lastname) VALUES ('$voter','$dni','$password', '$firstname', '$lastname')";
			if($conn->query($sql)){
				$response['status'] = 'success';
				$response['message'] = 'Votante agregado correctamente. Su ID de votante es: '.$voter.' no olvide ingresar correctamente su contraseña';
			} else {
				  $response['message'] = $conn->error;
			}
		}

	} else {
		 $response['message'] = 'Llena todos los campos primero.';
	} */
echo json_encode($response);
exit();
