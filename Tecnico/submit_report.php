<?php
require('../fpdf/fpdf.php'); // Asegúrate de que la ruta sea correcta
include('../config.php'); // Asegúrate de conectar la base de datos

// Validar si se reciben los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar datos del formulario
    $project_name = $_POST['project_name'];
    $report_date = $_POST['report_date'];
    $measurement_location = $_POST['measurement_location'];
    $measured_by = $_POST['measured_by'];
    $parameters = [
        ['name' => $_POST['parameter_1'], 'value' => $_POST['value_1'], 'tolerance' => $_POST['tolerance_1'], 'pass_fail' => $_POST['pass_fail_1']],
        ['name' => $_POST['parameter_2'], 'value' => $_POST['value_2'], 'tolerance' => $_POST['tolerance_2'], 'pass_fail' => $_POST['pass_fail_2']],
        ['name' => $_POST['parameter_3'], 'value' => $_POST['value_3'], 'tolerance' => $_POST['tolerance_3'], 'pass_fail' => $_POST['pass_fail_3']]
    ];
    $conclusion = $_POST['conclusion'];
    $prepared_by = $_POST['prepared_by'];
    $approved_by = $_POST['approved_by'];
    $calibration_date = $report_date;
    $interval = $_POST['measurement_interval'];
    $next_calibration = $_POST['due_date'];

    // Crear una instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Título del documento
    $pdf->Cell(0, 10, 'Measurement Report', 0, 1, 'C');

    // Información General
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'General Information', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, 'Project Name:', 0, 0);
    $pdf->Cell(50, 10, $project_name, 0, 1);
    $pdf->Cell(50, 10, 'Report Date:', 0, 0);
    $pdf->Cell(50, 10, $report_date, 0, 1);
    $pdf->Cell(50, 10, 'Measurement Location:', 0, 0);
    $pdf->Cell(50, 10, $measurement_location, 0, 1);
    $pdf->Cell(50, 10, 'Measured By:', 0, 0);
    $pdf->Cell(50, 10, $measured_by, 0, 1);

    // Detalles de la Medición
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Measurement Details', 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, 'Parameter', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Measured Value', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Tolerance', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Pass/Fail', 1, 1, 'C');
    $pdf->SetFont('Arial', '', 10);

    foreach ($parameters as $param) {
        if (!empty($param['name'])) {
            $pdf->Cell(40, 10, $param['name'], 1);
            $pdf->Cell(40, 10, $param['value'], 1);
            $pdf->Cell(40, 10, $param['tolerance'], 1);
            $pdf->Cell(40, 10, $param['pass_fail'], 1, 1);
        }
    }

    // Conclusión
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Conclusion', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, $conclusion);

    // Firmas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Signatures', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, 'Prepared By:', 0, 0);
    $pdf->Cell(50, 10, $prepared_by, 0, 1);
    $pdf->Cell(50, 10, 'Approved By:', 0, 0);
    $pdf->Cell(50, 10, $approved_by, 0, 1);

    // Guardar el PDF
    $save_path = '../measureResults/';
    if (!file_exists($save_path)) {
        mkdir($save_path, 0777, true); // Crear directorio si no existe
    }

    $file_name = 'certificate_' . $project_name . '_' . $calibration_date . '.pdf';
    $pdf->Output('F', $save_path . $file_name);

    // Insertar en la base de datos
    $insert_certificado_query = "INSERT INTO certificado (fecha, intervalo, archivo_pdf)
    VALUES ('$calibration_date', '$interval', '$file_name')";

    if (mysqli_query($conn, $insert_certificado_query)) {
        // Si la inserción en certificado es exitosa, obtenemos el último ID insertado
        $certificado_id = mysqli_insert_id($conn);

        // Actualizar la tabla `equipo` con la fecha de próxima calibración
        $update_equipo_query = "UPDATE equipo 
           SET proximaRecalibracion = '$next_calibration' 
           WHERE codigoEqp = '$project_name'";

        if (mysqli_query($conn, $update_equipo_query)) {
            echo "Registro insertado en certificado y actualizado en equipo con éxito.";
        } else {
            echo "Error al actualizar la tabla equipo: " . mysqli_error($conn);
        }
    } else {
        echo "Error al insertar en la tabla certificado: " . mysqli_error($conn);
    }
} else {
    echo "No se han recibido datos.";
}
?>
