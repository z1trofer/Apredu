<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../helpers/report_grado.php');


// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Establece la orientación del PDF como horizontal (paisaje)

// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['id_grado'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../entities/dto/grados.php');
    require_once('../entities/dto/empleados.php');
    require_once('../entities/dto/notas.php');
    // Se instancian las entidades correspondientes.
    $grados = new Grados;
    $empleados = new Empleados;
    $notas = new Notas;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($grados->setId($_GET['id_grado']) && $empleados->setid_grado($_GET['id_grado'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowGrado = $grados->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Actividades del grado: ' . $rowGrado['grado']. ' presente año');
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataGrados = $grados->gradoActividades()) {
                // Se establece un color de relleno para los encabezados.
                // Se establece la fuente para los encabezados.
                $pdf->setFillColor(154, 201, 229);
                $pdf->setFont('Arial', 'B', 11);
                // Se imprimen las celdas con los encabezados.

                $pdf->cell(50, 10, 'Actividad', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Tipo de actividad', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Asignatura', 1, 0, 'C', 1);
                $pdf->cell(120, 10, $pdf->encodeString('Descripción'), 1, 1, 'C', 1);

                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                // Se recorren los registros fila por fila.
                $trimestre = null;
                foreach ($dataGrados as $rowGrado) {
                    if($trimestre != $rowGrado['trimestre']){
                        $pdf->cell(250, 10, $pdf->encodeString($rowGrado['trimestre']), 1, 1, 'C', 1);
                        $trimestre = $rowGrado['trimestre'];
                    }
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(50, 10, $pdf->encodeString($rowGrado['nombre_actividad']), 'T', 0); // 'T' para el margen superior
                    $pdf->cell(40, 10, $pdf->encodeString($rowGrado['tipo_actividad']), 'T', 0); // 'T' para el margen superior
                    $pdf->cell(40, 10, $pdf->encodeString($rowGrado['asignatura']), 'T', 0); // 'T' para el margen superior
                    $pdf->MultiCell(120, 10, $pdf->encodeString($rowGrado['descripcion']), 'T', 1); // 'B' para los bordes, 'L' para alinear a la izquierda
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