<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/dto/grados.php');
require_once('../../entities/dto/estudiantes.php');

$grado = new Grados;
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Información de cada estudiante por grado');
// Se instancia el módelo Categoría para obtener los datos.

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataGrados = $grado->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(154, 201, 229);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(40, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(20, 10, 'NIE', 1, 0, 'C', 1);
    $pdf->cell(86, 10, $pdf->encodeString('Dirección'), 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(254, 227, 129);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataGrados as $rowGrado) {
        // Se imprime una celda con el nombre de la categoría.
        $pdf->setFillColor(254, 227, 129);
        $pdf->cell(186, 10, $pdf->encodeString('Grado: ' . $rowGrado['grado']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        $estudiante = new Estudiantes;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($estudiante->setIdGrado($rowGrado['id_grado'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataEstudiante = $estudiante->reporteEstudiantes()) {
                // Se recorren los registros fila por fila.
                foreach ($dataEstudiante as $rowEstudiante) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(40, 10, $pdf->encodeString($rowEstudiante['nombre_estudiante']), 1, 0);
                    $pdf->cell(40, 10, $pdf->encodeString($rowEstudiante['apellido_estudiante']), 1, 0, 'C');
                    $pdf->cell(20, 10, $rowEstudiante['nie'], 1, 0, 'C');
                    $pdf->cell(86, 10, $pdf->encodeString($rowEstudiante['direccion']), 1, 1, 'C');
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No existen estudiantes registrados en el grado'), 1, 1);
            }
        } else {
            $pdf->cell(0, 10, $pdf->encodeString('Grado incorrecto o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay categorías para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'estudiantes.pdf');
