<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/dto/notas.php');
require_once('../../entities/dto/grados.php');
require_once('../../entities/dto/actividades.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Notas de actividades por grado');
// Se instancia el módelo Categoría para obtener los datos.
$grados = new Grados;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataGrados = $grados->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(254, 227, 129);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Helvetica', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(100, 10, 'Nombre del estudiante', 1, 0, 'C', 1);
    $pdf->cell(56, 10, 'Actividad', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Nota', 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(154, 201, 229);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataGrados as $rowGrados) {
        // Se imprime una celda con el nombre de la categoría.
        $pdf->cell(0, 10, $pdf->encodeString('Grados: ' . $rowGrados['grado']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        $notas = new Notas;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($notas->setId_grado($rowGrados['id_grado'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataNotas = $notas->NotasDeEstudiantesPorActividades()) {
                // Se recorren los registros fila por fila.
                foreach ($dataNotas as $rowNotas) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(100, 10, $pdf->encodeString($rowNotas['nombre_estudiante']), 1, 0);
                    $pdf->cell(56, 10, $rowNotas['nombre_actividad'], 1, 0);
                    $pdf->cell(30, 10, $rowNotas['nota'], 1, 1);
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay notas ingresadas en una actividad en este grado'), 1, 1);
            }
        } else {
            $pdf->cell(0, 10, $pdf->encodeString('Grado incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay Grado para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'productos.pdf');
