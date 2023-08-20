<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/dto/empleados.php');
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Personal académico');
// Se instancia el módelo Categoría para obtener los datos.
$empleados = new Empleados;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataEmpleados = $empleados->readPorCargos()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(154, 201, 229);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Helvetica', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(62, 10, 'Nombres', 1, 0, 'C', 1);
    $pdf->cell(62, 10, 'Apellidos', 1, 0, 'C', 1);
    $pdf->cell(62, 10, $pdf->encodeString('Correo electrónico'), 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre del empleado
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('times', '', 11);

    // Se recorren los registros fila por fila.

    // Se recorren los registros fila por fila.
    foreach ($dataEmpleados as $rowEmpleado) {
        // Se imprimen las celdas con los datos de los productos.
        $pdf->cell(62, 10, $pdf->encodeString($rowEmpleado['nombre_empleado']), 1, 0);
        $pdf->cell(62, 10, $pdf->encodeString($rowEmpleado['apellido_empleado']),1, 0);
        $pdf->cell(62, 10, $pdf->encodeString($rowEmpleado['correo_empleado']),1, 1);
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay empleados para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Reporte del personal académico.pdf');

