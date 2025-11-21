<?php
	include 'includes/session.php';
    try {
       $sql = "WITH ranking AS (SELECT ca.id,ca.firstname AS nombre,ca.lastname AS apellido, COUNT(vo.candidate_id) AS cantidad FROM candidates ca LEFT JOIN votes vo ON vo.candidate_id = ca.id GROUP BY ca.id),maximo AS ( SELECT MAX(cantidad) AS max_votos FROM ranking) SELECT r.nombre, r.apellido, r.cantidad, ROUND((r.cantidad / m.max_votos) * 10, 0) AS puntos FROM ranking r CROSS JOIN maximo m ORDER BY puntos DESC";
		$query = $conn->query($sql);
		$data  = [];
        while ($row = $query->fetch_assoc()) {
            $data [] = $row;
        }
	    echo json_encode(['data' => $data]);
    } catch (\Throwable $th) {
        //throw $th;
        echo json_encode(['error' => $th->getMessage()]);
    }
exit();
