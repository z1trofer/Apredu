<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../helpers/report.php');

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../entities/dto/asignaturas.php');
require_once('../entities/dto/empleados.php');
require_once('../entities/dto/grados.php');

$asignatura = new Asignaturas;
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Asignaturas y personal académico por grado');
// Se instancia el módelo Categoría para obtener los datos.

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataAsignatura = $asignatura->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(154, 201, 229);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(80, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Cargo', 1, 0, 'C', 1);
    $pdf->cell(36, 10, 'Grado', 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(254, 227, 129);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataAsignatura as $rowAsignatura) {
        // Se imprime una celda con el nombre de la categoría.
        $pdf->setFillColor(254, 227, 129);
        $pdf->cell(186, 10, $pdf->encodeString('Asignatura: ' . $rowAsignatura['asignatura']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        $empleado = new Empleados;
        $grado = new Grados;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($empleado->setIdAsignatura($rowAsignatura['id_asignatura'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataAsignatura = $empleado->AsignaturaEmpleadoGrado()) {
                // Se recorren los registros fila por fila.
                foreach ($dataAsignatura as $rowDetalle) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(80, 10, $pdf->encodeString($rowDetalle['nombre_empleado']), 1, 0);
                    $pdf->cell(40, 10, $pdf->encodeString($rowDetalle['apellido_empleado']), 1, 0, 'C');
                    $pdf->cell(30, 10, $pdf->encodeString($rowDetalle['cargo']), 1, 0, 'C');
                    $pdf->cell(36, 10, $pdf->encodeString($rowDetalle['grado']), 1, 1);
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('no hay aisgnaturas'), 1, 1);
            }
        } else {
            $pdf->cell(0, 10, $pdf->encodeString('Asignatura incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay asignaturas para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'productos.pdf');
