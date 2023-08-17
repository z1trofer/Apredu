<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/dto/empleados.php');
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Empleados');
// Se instancia el módelo Categoría para obtener los datos.
$cargo = new Cargos;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataCargo = $cargo->readCargos()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(175);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(62, 10, 'Nombres', 1, 0, 'C', 1);
    $pdf->cell(62, 10, 'Apellidos', 1, 0, 'C', 1);
    $pdf->cell(62, 10, 'Correo electrónico', 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre del empleado
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataCargo as $rowCargo) {
        // Se imprime una celda con el nombre del empleado.
        $pdf->cell(0, 10, $pdf->encodeString('Cargos: ' . $rowCargo['cargo']), 1, 1, 'C', 1);
        // Se instancia el módelo empleado para procesar los datos.
        $empleado = new Empleados;
        // Se establece el cargo para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($empleado->setid_cargo($rowCargo['id_cargo'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataCargo = $empleado->readPorCargos()) {
                // Se recorren los registros fila por fila.
                foreach ($dataEmpleados as $rowEmpleado) {
                    $pdf->cell(62, 10, $pdf->encodeString($rowEmpleado['nombre_empleado']), 1, 0);
                    $pdf->cell(62, 10, $rowEmpleado['apellido_empleado'], 1, 0);
                    $pdf->cell(62, 10, $rowEmpleado['correo_electronico'], 1, 0);
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay empleados para este cargo'), 1, 1);
            }
        } else {
            $pdf->cell(0, 10, $pdf->encodeString('Cargo incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay cargos para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'personal.pdf');
