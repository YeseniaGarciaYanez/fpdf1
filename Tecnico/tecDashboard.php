<?php
require_once '../config.php';
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['nombre']) || $_SESSION['tipo_usuario'] !== 'tecnico') {
    header('Location: ../customerPortal.php');
    exit();
}

$nombre = $_SESSION['nombre'];

// Consulta para obtener los servicios pendientes
$serviciosPendientes = [];
$query = "SELECT pedido.CodigoPedido, pedido.fechaPedido, pedido.estado, equipo.codigoEqp AS equipo, categoria.nombre AS categoria 
          FROM pedido
          JOIN equipo ON pedido.equipo = equipo.codigoEqp
          JOIN categoria ON equipo.categoria = categoria.codigo
          WHERE pedido.estado IN ('Pending', 'Review')";




$result = $conn->query($query);

// Verificar si hay resultados
$serviciosPendientes = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $serviciosPendientes[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>




<?php
// Inicio del buffer de salida
ob_start();

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoReporte = $_POST['tipoReporte'] ?? null;
    $templateSeleccionado = $_POST['templateSeleccionado'] ?? null;

    // Validar que se haya seleccionado un tipo de reporte y un template
    if (!$tipoReporte || !$templateSeleccionado) {
        die('Por favor, selecciona un tipo de reporte y un template.');
    }

    // Determinar redirección según el tipo de reporte y el template seleccionado
    switch ($tipoReporte) {
        case 'Calibracion':
            if (in_array($templateSeleccionado, ['Flow', 'Multimeter', 'Power Supply', 'Pressure', 'Scale', 'Signal Analyzer', 'Temperature'])) {
                // Redirigir al template correspondiente de calibración
                header('Location: ../templates/calibration/calibration_' . strtolower($templateSeleccionado) . '.php');
                exit();
            }
            break;

        case 'Medicion':
            if ($templateSeleccionado === 'Measurement') {
                header('Location: ../templates/measurement/measurement.php');
                exit();
            }
            break;
        default:
            die('Tipo de reporte no válido.');
    }
    die('Template no válido o no encontrado.');
}

// Cerrar buffer de salida
ob_end_flush();
?>





<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/tecDashboard.css">
    <title>Technician Dashboard - CaliRisk</title>
</head>
<body>
    <!-- Sidebar paa navegación -->
    <div class="sidebar">
        <ul>
            <li><a href="#mis-servicios" onclick="mostrarSeccion('mis-servicios')">My Services</a></li>
            <li><a href="#reportes" onclick="mostrarSeccion('reportes')">Reports</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </div>

    <header>
    <img src= https://i.imgur.com/WDqrhaM.png alt="Logo" class="header-logo">
        <h1>Welcome, <?php echo $_SESSION['nombre']; ?>!</h1>
    </header>

    <main>
    <section id="mis-servicios">
    <h2>My Services</h2>
    <p>Here you can see the pending services.</p>
    <table>
        <thead>
            <tr>
                <th>Order Code</th>
                <th>Date Ordered</th>
                <th>Status</th>
                <th>Equipment</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($serviciosPendientes)): ?>
                <?php foreach ($serviciosPendientes as $servicio): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($servicio['CodigoPedido'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($servicio['fechaPedido'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($servicio['estado'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($servicio['equipo'] ?? 'Not available'); ?></td> 
                        <td><?php echo htmlspecialchars($servicio['categoria'] ?? 'Not available'); ?></td>
                        <td>
                            <button onclick="mostrarFormularioReporte(<?php echo htmlspecialchars(json_encode($servicio)); ?>)">Perform service</button>
                            <button type="button" onclick="mostrarFormularioActualizar('<?php echo $servicio['CodigoPedido']; ?>')">Update status</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">There are no outstanding services.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Formulario oculto para seleccionar el tipo de reporte y template -->
    <form id="formularioReporte" method="POST" style="display: none;">
        <h3>Select the type of report</h3>
        <label for="tipoReporte">Type of Report:</label>
        <select name="tipoReporte" id="tipoReporte" onchange="mostrarTemplates()">
            <option value="">Select...</option>
            <option value="Calibracion">Calibration</option>
            <option value="Medicion">Measurement</option>
            <!--<option value="AmbientesControlados">Controlled Environments</option>-->
        </select>
        
        <label for="templateSeleccionado">Template:</label>
        <select name="templateSeleccionado" id="templateSeleccionado">
            <option value="">First select a type of report...</option>
        </select>

        <button type="submit">Generate Report</button>
    </form>

    <!-- Formulario de actualización de estado y resultado (oculto por defecto) -->
    <div id="formularioActualizar" style="display:none; margin-top: 20px;">
        <form action="actualizarEstado.php" method="post">
            <input type="hidden" name="pedido_id">
            <label for="estado">Status:</label>
            <select name="estado" required>
                <option value="Review">Review</option>
            </select>
            <label for="resultado">Result:</label>
            <select name="resultado" required>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
            </select>
            <button type="submit">Update</button>
        </form>
    </div>
</section>


<script>
function mostrarFormularioReporte(servicio) {
    document.getElementById('formularioReporte').style.display = 'block';
}

function mostrarTemplates() {
    const tipoReporte = document.getElementById('tipoReporte').value;
    const templateSelect = document.getElementById('templateSeleccionado');

    templateSelect.innerHTML = ''; // Limpia las opciones previas

    let templates = [];

    // Asigna los templates basados en el tipo de reporte seleccionado
    if (tipoReporte === 'Calibracion') {
        templates = ['Flow', 'Multimeter', 'Power Supply', 'Pressure', 'Scale', 'Signal Analyzer', 'Temperature'];
    } else if (tipoReporte === 'Medicion') {
        templates = ['Measurement'];/*
    } else if (tipoReporte === 'AmbientesControlados') {
        templates = ['Ambientes Controlados'];*/
    }

    // Crea las opciones de template
    templates.forEach(template => {
        const option = document.createElement('option');
        option.value = template;
        option.textContent = template;
        templateSelect.appendChild(option);
    });
}
</script>

<script>
function mostrarFormularioActualizar(pedido_id) {
    console.log("Formulario de actualización de estado debería mostrarse"); // Verifica si se está ejecutando la función
    console.log("Pedido ID: ", pedido_id); // Muestra el valor de pedido_id en la consola
    document.getElementById("formularioActualizar").style.display = "block";
    document.querySelector("input[name='pedido_id']").value = pedido_id;
}



</script>


        
        <!--DEFINIR ESTA SECCION creo que no se va a usar xd

        <section id="equipos-asignados">
            <h2>Equipos Asignados</h2>
            <p>Aquí puedes consultar y gestionar los equipos que te han sido asignados.</p>
        </section>
        -->

        <section id="reportes">
    <h2>Certificates Query</h2>
    <p>The list of certificates uploaded by the technicians is shown below.</p>

    <!-- Barra de búsqueda -->
    <label for="busqueda">Certificate search:</label>
    <input type="text" id="busqueda" name="busqueda" placeholder="Search...">

    <!-- Tabla de certificados -->
    <table class="tabla-certificados">
        <thead>
            <tr>
                <th>Certificate Code</th>
                <th>Date of Issue</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="certificados-lista">
            <!-- Aquí se mostrarán los resultados de los certificados -->
        </tbody>
    </table>
</section>

<script>
// Función para cargar los certificados o filtrar por búsqueda
function cargarCertificados(busqueda = '') {
    // Crear una solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'buscar_certificados.php?busqueda=' + busqueda, true);

    // Cuando el servidor responda, actualizar la tabla
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('certificados-lista').innerHTML = xhr.responseText;
        }
    };

    // Enviar la solicitud
    xhr.send();
}

// Llamar a la función al cargar la página para mostrar todos los certificados por defecto
window.onload = function() {
    cargarCertificados();  // Esto cargará todos los certificados si la búsqueda está vacía
};

// Añadir el evento keyup al campo de búsqueda para filtrar en tiempo real
document.getElementById('busqueda').addEventListener('keyup', function() {
    // Obtener el valor de búsqueda
    var busqueda = this.value;
    // Llamar a la función cargarCertificados con el término de búsqueda
    cargarCertificados(busqueda);
});
</script>




    </main>

    <footer>
        <p>&copy; 2024 CaliRisk. All rights reserved.</p>
    </footer>


    <script>
        function mostrarSeccion(idSeccion) {
            var secciones = document.querySelectorAll('main section');
            secciones.forEach(function(seccion) {
                seccion.style.display = 'none';
            });
            document.getElementById(idSeccion).style.display = 'block';
        }

        window.onload = function() {
            mostrarSeccion('mis-servicios');
        }
    </script>
</body>
</html>
