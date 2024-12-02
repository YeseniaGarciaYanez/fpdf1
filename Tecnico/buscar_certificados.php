<?php
// Incluir el archivo de configuración de la base de datos
include '../config.php';

// Activar el logging de errores para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener el valor de búsqueda desde el parámetro GET
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Consulta para obtener todos los certificados
$query = "
    SELECT 
        c.codigo AS codigoCertificado, 
        c.fecha AS fechaExpedicion, 
        DATE_ADD(c.fecha, INTERVAL c.intervalo MONTH) AS fechaVencimiento
    FROM certificado c
";


// Ejecutar la consulta y verificar si se ejecutó correctamente
$result = $conn->query($query);
if (!$result) {
    error_log("Error en la consulta: " . $conn->error);
    echo "<tr><td colspan='5'>Error al ejecutar la consulta.</td></tr>";
    exit; // Salir si hay un error en la consulta
}

// Verificar si hay resultados y mostrarlos
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['codigoCertificado'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['fechaExpedicion'] ?? 'N/A') . "</td>";
        
        // Ruta del archivo PDF (se usará el código del certificado)
        $pdf_path = "../certificate/" . htmlspecialchars($row['codigoCertificado'] ?? '');

        // Mostrar el botón para ver o descargar el PDF
        echo "<td>";
        if (!empty($row['codigoCertificado'])) {
            echo "<a href='$pdf_path' target='_blank'><button class='button'>View PDF</button></a>";
        } else {
            echo "<button class='button' disabled>No available</button>";
        }
        echo "</td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No se encontraron certificados.</td></tr>";
}

// Cerrar la conexión
$conn->close();
?>
