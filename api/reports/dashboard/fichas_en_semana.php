<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/dto/fichas.php');
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Fichas por semana');
// Se instancia el módelo Categoría para obtener los datos.
$fichas = new Fichas;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataFichas = $fichas->readFichasPorSemana()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(154, 201, 229);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(35, 10, 'Nombres', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Apellidos', 1, 0, 'C', 1);
    $pdf->cell(91, 10, $pdf->encodeString('Descripción'), 1, 0, 'C', 1);
    $pdf->cell(25, 10, 'Fecha', 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre del empleado
    $pdf->setFillColor(254, 227, 129);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 11);

    // Se recorren los registros fila por fila.

    // Se recorren los registros fila por fila.
    foreach ($dataFichas as $rowFichas) {
        // Se imprimen las celdas con los datos de los productos.
        $pdf->cell(35, 10, $pdf->encodeString($rowFichas['nombre_estudiante']), 1, 0);
        $pdf->cell(35, 10, $pdf->encodeString($rowFichas['apellido_estudiante']),1, 0);
        $pdf->cell(91, 10, $pdf->encodeString($rowFichas['descripcion_ficha']),1, 0);
        $pdf->cell(25, 10, $pdf->encodeString($rowFichas['fecha_ficha']), 1, 1);
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay fichas para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Reporte Semanal de Fichas de conducta.pdf');

