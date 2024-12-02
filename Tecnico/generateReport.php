<?php
require('../fpdf/fpdf.php');  // Asegúrate de que FPDF esté en la carpeta correcta o esté correctamente incluido
require('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $client = $_POST['client'];
    $calibration_date = $_POST['calibration_date'];
    $client_address = $_POST['client_address'];
    $requester = $_POST['requester'];
    $interval = $_POST['interval'];

    // Información del equipo
    $equipment = $_POST['equipment'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $client_id = $_POST['client_id'];
    $instrument_type = $_POST['instrument_type'];
    $measurement_range = $_POST['measurement_range'];

    // Condiciones ambientales
    $initial_temperature = $_POST['initial_temperature'];
    $final_temperature = $_POST['final_temperature'];
    $initial_humidity = $_POST['initial_humidity'];
    $final_humidity = $_POST['final_humidity'];

    // Estándares de referencia
    $reference_equipment = $_POST['standard_equipment'];
    $reference_serial_number = $_POST['standard_serial_number'];
    $reference_certificate_number = $_POST['standard_certificate_number'];
    $reference_traceability = $_POST['standard_traceability'];

    // Resultados de calibración
    $calibration_point = $_POST['calibration_point'];
    $reference_value = $_POST['standard_value'];
    $dut_value = $_POST['dut_value'];
    $error = $_POST['error'];
    $uncertainty = $_POST['uncertainty'];
    $applicable_standard = $_POST['applicable_standard'];

    // Observaciones
    $observations = $_POST['observations'];

    // Firmas
    $performed_by = isset($_POST['performed_by']) ? $_POST['performed_by'] : '';
    $performed_position = isset($_POST['performed_position']) ? $_POST['performed_position'] : '';
    $reviewed_by = isset($_POST['reviewed_by']) ? $_POST['reviewed_by'] : '';
    $reviewed_position = isset($_POST['reviewed_position']) ? $_POST['reviewed_position'] : '';
    $approved_by = isset($_POST['approved_by']) ? $_POST['approved_by'] : '';
    $approved_position = isset($_POST['approved_position']) ? $_POST['approved_position'] : '';

    // Crear una nueva instancia de FPDF
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // Establecer una fuente para todo el documento
    $pdf->SetFont('Arial', '', 12);

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(61, 86, 219);  // Color azul similar al que mencionas
    $pdf->Cell(0, 10, 'Calibration Certificate - Temperature Instruments', 0, 1, 'C');
    $pdf->Ln(10);

    // Información General
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(61, 86, 219);  // Color de fondo para encabezado
    $pdf->SetTextColor(255, 255, 255);  // Color blanco para el texto
    $pdf->Cell(0, 10, '1. General Information', 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);  // Color negro para el texto
    $pdf->MultiCell(0, 10, 'Client: ' . $client);
    $pdf->MultiCell(0, 10, 'Calibration Date: ' . $calibration_date);
    $pdf->MultiCell(0, 10, 'Interval: ' . $interval);
    $pdf->MultiCell(0, 10, 'Address: ' . $client_address);
    $pdf->MultiCell(0, 10, 'Requested by: ' . $requester);
    $pdf->Ln(10);

    // Información del equipo
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(61, 86, 219);  // Color de fondo para encabezado
    $pdf->SetTextColor(255, 255, 255);  // Color blanco para el texto
    $pdf->Cell(0, 10, '2. Equipment Information', 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);  // Color negro para el texto
    $pdf->MultiCell(0, 10, 'Equipment: ' . $equipment);
    $pdf->MultiCell(0, 10, 'Brand: ' . $brand);
    $pdf->MultiCell(0, 10, 'Model: ' . $model);
    $pdf->MultiCell(0, 10, 'Client ID: ' . $client_id);
    $pdf->MultiCell(0, 10, 'Instrument Type: ' . $instrument_type);
    $pdf->MultiCell(0, 10, 'Measurement Range: ' . $measurement_range);
    $pdf->Ln(10);

    // Condiciones ambientales
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(61, 86, 219);  // Color de fondo para encabezado
    $pdf->SetTextColor(255, 255, 255);  // Color blanco para el texto
    $pdf->Cell(0, 10, '3. Environmental Conditions', 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);  // Color negro para el texto
    $pdf->MultiCell(0, 10, 'Initial Temperature: ' . $initial_temperature . ' °C');
    $pdf->MultiCell(0, 10, 'Final Temperature: ' . $final_temperature . ' °C');
    $pdf->MultiCell(0, 10, 'Initial Humidity: ' . $initial_humidity . ' %RH');
    $pdf->MultiCell(0, 10, 'Final Humidity: ' . $final_humidity . ' %RH');
    $pdf->Ln(10);

    // Estándares de referencia
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(61, 86, 219);  // Color de fondo para encabezado
    $pdf->SetTextColor(255, 255, 255);  // Color blanco para el texto
    $pdf->Cell(0, 10, '4. Reference Standards', 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);  // Color negro para el texto
    $pdf->MultiCell(0, 10, 'Equipment: ' . $reference_equipment);
    $pdf->MultiCell(0, 10, 'Serial Number: ' . $reference_serial_number);
    $pdf->MultiCell(0, 10, 'Certificate Number: ' . $reference_certificate_number);
    $pdf->MultiCell(0, 10, 'Traceability: ' . $reference_traceability);
    $pdf->Ln(10);

    // Resultados de calibración
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(61, 86, 219);  // Color de fondo para encabezado
    $pdf->SetTextColor(255, 255, 255);  // Color blanco para el texto
    $pdf->Cell(0, 10, '5. Calibration Results', 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);  // Color negro para el texto
    $pdf->MultiCell(0, 10, 'Calibration Point: ' . $calibration_point);
    $pdf->MultiCell(0, 10, 'Reference Value: ' . $reference_value);
    $pdf->MultiCell(0, 10, 'DUT Value: ' . $dut_value);
    $pdf->MultiCell(0, 10, 'Error: ' . $error);
    $pdf->MultiCell(0, 10, 'Uncertainty: ' . $uncertainty);
    $pdf->MultiCell(0, 10, 'Applicable Standard: ' . $applicable_standard);
    $pdf->Ln(10);

    // Observaciones
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(61, 86, 219);  // Color de fondo para encabezado
    $pdf->SetTextColor(255, 255, 255);  // Color blanco para el texto
    $pdf->Cell(0, 10, '6. Observations', 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);  // Color negro para el texto
    $pdf->MultiCell(0, 10, $observations);
    $pdf->Ln(10);

    // Firmas (sin líneas horizontales)
        // Resultados de calibración
        $pdf->SetFillColor(61, 86, 219);  // Color de fondo para encabezado
        $pdf->Cell(0, 10, '7. Signatures', 0, 1, 'L', true);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Performed by: ' . $performed_by, 0, 0, 'C');
    $pdf->Cell(60, 10, 'Position: ' . $performed_position, 0, 1, 'C');
    $pdf->Cell(60, 10, 'Reviewed by: ' . $reviewed_by, 0, 0, 'C');
    $pdf->Cell(60, 10, 'Position: ' . $reviewed_position, 0, 1, 'C');
    $pdf->Cell(60, 10, 'Approved by: ' . $approved_by, 0, 0, 'C');
    $pdf->Cell(60, 10, 'Position: ' . $approved_position, 0, 1, 'C');

// Obtener la fecha actual en el formato deseado (por ejemplo, YYYY-MM-DD)
$current_date = date('Y-m-d');

// Suponiendo que tienes el código del equipo en la variable $equipment_code
$equipment_code = $_POST['equipment'];  // O como lo obtengas

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

// Preparar y ejecutar la consulta SQL
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fecha, $intervalo, $archivo_pdf);

// Asignar los valores a los parámetros
$fecha = $current_date;  // Fecha de creación del certificado
$intervalo = $interval;  // Intervalo (fecha de vencimiento del certificado)
$archivo_pdf = $pdf_filename;  // Nombre del archivo PDF

// Ejecutar la consulta
$stmt->execute();

// Verificar si la inserción fue exitosa
if ($stmt->affected_rows > 0) {
    echo "Certificado insertado correctamente.";
} else {
    echo "Hubo un error al insertar el certificado.";
}

// Cerrar la conexión
$stmt->close();
$conn->close();

header("Location: tecDashboard.php#mis-servicios");  // Redirige a la sección de mis-servicios
exit();


}
?>


