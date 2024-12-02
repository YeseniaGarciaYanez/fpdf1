<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pedido_id = $_POST['pedido_id'];
    $estado = $_POST['estado'];
    $resultado = $_POST['resultado'];

    // Verifica si el pedido_id está definido
    if (isset($pedido_id)) {
        echo "Order ID received: " . $pedido_id;
    }

    // Consulta SQL
    $update_query = "UPDATE pedido SET estado = '$estado', resultado = '$resultado' WHERE CodigoPedido = '$pedido_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "Correctly updated status and results.";
    } else {
        echo "Error while updating: " . mysqli_error($conn);
    }

header("Location: tecDashboard.php#mis-servicios");  // Redirige a la sección de mis-servicios
exit();
}
?>