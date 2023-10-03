<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../helpers/report.php');

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../entities/dto/grados.php');
require_once('../entities/dto/empleados.php');
require_once('../entities/dto/asignaturas.php');

$grado = new Grados;
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Información de las asignaturas existentes en un grado');
// Se instancia el módelo Categoría para obtener los datos.

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataGrados = $grado->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(154, 201, 229);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(186, 10, 'Asignatura', 1, 1, 'C', 1);


    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(254, 227, 129);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataGrados as $rowGrado) {
        // Se imprime una celda con el nombre de la categoría.
        $pdf->setFillColor(254, 227, 129);
        $pdf->cell(186, 10, $pdf->encodeString('Grado: ' . $rowGrado['grado']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        $empleado = new Empleados;
        $estudiante = new Asignaturas;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($empleado->setid_grado($rowGrado['id_grado'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataAsignatura = $empleado->gradoAsignaturas()) {
                // Se recorren los registros fila por fila.
                foreach ($dataAsignatura as $rowAsignatura) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(186, 10, $pdf->encodeString($rowAsignatura['asignatura']), 1, 1, 'C');
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No existen asignaturas registrados en el grado'), 1, 1);
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
