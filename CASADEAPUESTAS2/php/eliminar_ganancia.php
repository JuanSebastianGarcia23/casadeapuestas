<?php
session_start();
include 'conexion_be.php';

header('Content-Type: application/json');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

// Obtener datos JSON enviados desde el cliente
$input = json_decode(file_get_contents('php://input'), true);

// Validar que el ID esté presente y sea numérico
if (!isset($input['id']) || !is_numeric($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit();
}

$idEliminar = intval($input['id']);

// Verificar que el registro a eliminar pertenece al usuario autenticado
$sqlVerif = "SELECT id FROM historial_ganancias WHERE id = ? AND id_usuario = ?";
$stmtVerif = $conexion->prepare($sqlVerif);
$stmtVerif->bind_param("ii", $idEliminar, $idUsuario);
$stmtVerif->execute();
$resultVerif = $stmtVerif->get_result();

if ($resultVerif->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Registro no encontrado o no autorizado']);
    exit();
}

// Ejecutar eliminación
$sqlEliminar = "DELETE FROM historial_ganancias WHERE id = ?";
$stmtEliminar = $conexion->prepare($sqlEliminar);
$stmtEliminar->bind_param("i", $idEliminar);

if ($stmtEliminar->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el registro']);
}
?>
