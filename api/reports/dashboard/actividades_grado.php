<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');


// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['id_grado'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../entities/dto/grados.php');
    require_once('../../entities/dto/empleados.php');
    // Se instancian las entidades correspondientes.
    $grados = new Grados;
    $empleados = new Empleados;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($grados->setId($_GET['id_grado']) && $empleados->setid_grado($_GET['id_grado'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowGrado = $grados->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Actividades del grado: ' . $rowGrado['grado']);
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataGrados = $grados->gradoActividades()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(215, 198, 153);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(40, 10, 'Actividad', 1, 0, 'C', 1);
                $pdf->cell(50, 10, $pdf->encodeString('Descripción'), 1, 0, 'C', 1);
                $pdf->cell(20, 10, $pdf->encodeString('Ponderación %'), 1, 1, 'C', 1);
                $pdf->cell(40, 10, 'Tipo de actividad', 1, 1, 'C', 1);
                $pdf->cell(36, 10, 'Asignatura', 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Helvetica', '', 11);
                // Se recorren los registros fila por fila.
                foreach ($dataGrados as $rowGrado) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(40, 10, $pdf->encodeString($rowGrado['nombre_actividad']), 'B', 0);
                    $pdf->cell(50, 10, $pdf->encodeString($rowGrado['descripcion']), 'B', 0);
                    $pdf->cell(20, 10, 'ponderacion', 'B', 0);
                    $pdf->cell(40, 10, $pdf->encodeString($rowGrado['tipo_actividad']), 'B', 0);
                    $pdf->cell(36, 10, $pdf->encodeString($rowGrado['asignatura']), 'B', 1);
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay actividades en este grado'), 1, 1);
            }
            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'Actividades.pdf');
        } else {
            print('Subcategoría inexistente');
        }
    } else {
        print('Subcategoría incorrecta');
    }
} else {
    print('Debe seleccionar una Subcategoría');
}