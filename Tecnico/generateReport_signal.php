<?php
require('../fpdf/fpdf.php');
include('../config.php'); // Asegúrate de incluir la conexión a la base de datos

// Recibir los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client = $_POST['client'];
    $calibration_date = $_POST['calibration_date'];
    $client_address = $_POST['client_address'];
    $requested_by = $_POST['requested_by'];
    $next_calibration = $_POST['next_calibration'];
    $equipment_name = $_POST['equipment_name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $client_id = $_POST['client_id'];
    $calibrated_functions = $_POST['calibrated_functions'];
    $measurement_range = $_POST['measurement_range'];
    $initial_temperature = $_POST['initial_temperature'];
    $final_temperature = $_POST['final_temperature'];
    $initial_humidity = $_POST['initial_humidity'];
    $final_humidity = $_POST['final_humidity'];
    $reference_equipment = $_POST['reference_equipment'];
    $reference_serial_number = $_POST['reference_serial_number'];
    $reference_certificate_number = $_POST['reference_certificate_number'];
    $reference_traceability = $_POST['reference_traceability'];
    $function = $_POST['function'];
    $range = $_POST['range'];
    $reference_value = $_POST['reference_value'];
    $dut_value = $_POST['dut_value'];
    $error = $_POST['error'];
    $uncertainty = $_POST['uncertainty'];
    $observations = $_POST['observations'];
    $performed_by = $_POST['performed_by'];
    $performed_by_role = $_POST['performed_by_role'];
    $reviewed_by = $_POST['reviewed_by'];
    $reviewed_by_role = $_POST['reviewed_by_role'];
    $approved_by = $_POST['approved_by'];
    $approved_by_role = $_POST['approved_by_role'];
    $interval = $_POST['interval'];

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Establecer márgenes
$pdf->SetMargins(10, 10, 10);

// Título con fondo de color
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetFillColor(52, 86, 219); // Color de fondo azul
$pdf->Cell(0, 15, 'Calibration Certificate - Signal Analyzers', 0, 1, 'C', true);
$pdf->Ln(10); // Espacio adicional después del título

// Información general
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 18, 'Client:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $client, 1, 1, 'L');
$pdf->Cell(40, 18, 'Calibration Date:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $calibration_date, 1, 1, 'L');
$pdf->Cell(40, 18, 'Address:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $client_address, 1, 1, 'L');
$pdf->Cell(40, 18, 'Requested by:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $requested_by, 1, 1, 'L');
$pdf->Cell(40, 18, 'Next Calibration:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $next_calibration, 1, 1, 'L');
$pdf->Cell(40, 18, 'Interval:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $interval, 1, 1, 'L');

// Línea de separación
$pdf->Ln(8); // Espacio adicional entre secciones
$pdf->SetDrawColor(0, 0, 0); // Color de la línea (negro)
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Línea horizontal
$pdf->Ln(8); // Espacio después de la línea

// Información del instrumento
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 18, 'Equipment:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $equipment_name, 1, 1, 'L');
$pdf->Cell(50, 18, 'Brand:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $brand, 1, 1, 'L');
$pdf->Cell(50, 18, 'Model:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $model, 1, 1, 'L');
$pdf->Cell(50, 18, 'Client ID:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $client_id, 1, 1, 'L');

// Línea de separación
$pdf->Ln(8);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(8);

// Condiciones ambientales en una tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 12, 'Temperature:', 1, 0, 'L', true);
$pdf->Cell(0, 12, 'Initial: ' . $initial_temperature . ' °C, Final: ' . $final_temperature . ' °C', 1, 1, 'L');
$pdf->Cell(40, 12, 'Relative Humidity:', 1, 0, 'L', true);
$pdf->Cell(0, 12, 'Initial: ' . $initial_humidity . '%RH, Final: ' . $final_humidity . '%RH', 1, 1, 'L');

// Línea de separación
$pdf->Ln(8);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(8);

// Estándares de referencia (ancho aumentado)
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 18, 'Reference Equipment:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $reference_equipment, 1, 1, 'L');
$pdf->Cell(50, 18, 'Serial Number:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $reference_serial_number, 1, 1, 'L');
$pdf->Cell(50, 18, 'Certificate Number:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $reference_certificate_number, 1, 1, 'L');
$pdf->Cell(50, 18, 'Traceability:', 1, 0, 'L', true); // Aumentar el ancho de la celda
$pdf->Cell(0, 18, $reference_traceability, 1, 1, 'L');

// Línea de separación
$pdf->Ln(8);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(8);

// Resultados de la calibración en una tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 18, 'Function:', 1, 0, 'L', true);
$pdf->Cell(0, 18, $function, 1, 1, 'L');
$pdf->Cell(40, 18, 'Range:', 1, 0, 'L', true);
$pdf->Cell(0, 18, $range, 1, 1, 'L');
$pdf->Cell(40, 12, 'Reference Value:', 1, 0, 'L', true);
$pdf->Cell(0, 12, $reference_value, 1, 1, 'L');
$pdf->Cell(40, 12, 'DUT Value:', 1, 0, 'L', true);
$pdf->Cell(0, 12, $dut_value, 1, 1, 'L');
$pdf->Cell(40, 12, 'Error:', 1, 0, 'L', true);
$pdf->Cell(0, 12, $error, 1, 1, 'L');
$pdf->Cell(40, 12, 'Uncertainty:', 1, 0, 'L', true);
$pdf->Cell(0, 12, $uncertainty, 1, 1, 'L');
$pdf->Cell(0, 12, 'The calibration was performed according to the applicable standard.', 1, 1, 'L');

// Observaciones
$pdf->Ln(15); // Más espacio antes de las observaciones
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 12, '6. Observations', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 12, $observations);

// Firmas
$pdf->Ln(35); // Espacio para las firmas
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 12, '7. Signatures', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 12, 'Performed by:', 0, 0);
$pdf->Cell(0, 12, $performed_by . ' (' . $performed_by_role . ')', 0, 1);
$pdf->Cell(40, 12, 'Reviewed by:', 0, 0);
$pdf->Cell(0, 12, $reviewed_by . ' (' . $reviewed_by_role . ')', 0, 1);
$pdf->Cell(40, 12, 'Approved by:', 0, 0);
$pdf->Cell(0, 12, $approved_by . ' (' . $approved_by_role . ')', 0, 1);


// Guardar el PDF
$save_path = '../certificate/';
if (!file_exists($save_path)) {
    mkdir($save_path, 0777, true); // Crear el directorio si no existe
}

// Inserta el registro en la tabla certificado sin el archivo PDF
$insert_certificado_query = "INSERT INTO certificado (fecha, intervalo) VALUES ('$calibration_date', '$interval')";
$conn->query($insert_certificado_query);

// Obtener el código del certificado recién generado por el trigger
// Puedes usar la fecha y otros valores únicos en la consulta si es necesario
$get_codigo_query = "SELECT codigo FROM certificado WHERE fecha = '$calibration_date' ORDER BY codigo DESC LIMIT 1";
$result = $conn->query($get_codigo_query);
$row = $result->fetch_assoc();
$certificado_codigo = $row['codigo'];

// Genera el nombre del archivo PDF utilizando el código del certificado
$file_name =$certificado_codigo;
$pdf->Output('F', $save_path . $file_name);


// Ejecutamos la consulta para insertar en la tabla certificado
if (mysqli_query($conn, $insert_certificado_query)) {
// Si la inserción en certificado es exitosa, obtenemos el último ID insertado
$certificado_id = mysqli_insert_id($conn);

// 2. Actualizar la tabla `equipo` con la fecha de próxima calibración
// Usamos la variable $next_calibration que ya contiene la fecha calculada previamente
$update_equipo_query = "UPDATE equipo 
   SET proximaRecalibracion = '$next_calibration' 
   WHERE codigoEqp = '$equipment_name'";

// Ejecutamos la consulta para actualizar la tabla equipo
if (mysqli_query($conn, $update_equipo_query)) {
// Si ambas operaciones son exitosas
echo "Registro insertado en certificado y actualizado en equipo con éxito.";
} else {
// Si hay un error en la actualización del equipo
echo "Error al actualizar la tabla equipo: " . mysqli_error($conn);
}
} else {
// Si hay un error en la inserción de certificado
echo "Error al insertar en la tabla certificado: " . mysqli_error($conn);
}
} else {
    echo "No se han recibido datos.";
}
header("Location: tecDashboard.php#mis-servicios");  // Redirige a la sección de mis-servicios
exit();

?>
