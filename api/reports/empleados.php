<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../entities/dto/empleados.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Personal académico');
// Se instancia el módelo Categoría para obtener los datos.
$empleados = new Empleados;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataCargo = $empleados->readCargos()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(154, 201, 229);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(50, 10, 'Nombres', 1, 0, 'C', 1);
    $pdf->cell(50, 10, 'Apellidos', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'DUI', 1, 0, 'C', 1);
    $pdf->cell(56, 10,$pdf->encodeString( 'Correo electrónico'), 1, 1, 'C', 1);


    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(254, 227, 129);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataCargo as $rowCargo) {
        // Se imprime una celda con el nombre de la categoría.
        $pdf->cell(0, 10, $pdf->encodeString('Cargo: ' . $rowCargo['cargo']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($empleados->setid_cargo($rowCargo['id_cargo'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataEmpleados = $empleados->readPorCargos()) {
                // Se recorren los registros fila por fila.
                foreach ($dataEmpleados as $rowEmpleados) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(50, 10, $pdf->encodeString($rowEmpleados['nombre_empleado']), 1, 0);
                    $pdf->cell(50, 10, $pdf->encodeString($rowEmpleados['apellido_empleado']), 1, 0);
                    $pdf->cell(30, 10, $pdf->encodeString($rowEmpleados['dui']), 1, 0);
                    $pdf->cell(56, 10, $pdf->encodeString($rowEmpleados['correo_empleado']), 1, 1);

                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay empleados para este cargo'), 1, 1);
            }
        } else {
            $pdf->cell(0, 10, $pdf->encodeString('Cargo incorrecto o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay cargo para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Personal.pdf');
