<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../helpers/report.php');


// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['id_estudiante'])) {
    require_once('../entities/dto/estudiantes.php');
    require_once('../entities/dto/fichas.php');
    // Se instancian las entidades correspondientes.
    $fichas = new Fichas;
    $estudiante = new Estudiantes;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($estudiante->setIdEstudiante($_GET['id_estudiante']) && $fichas->setIdEstudiante($_GET['id_estudiante'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        // Se inicia el reporte con el encabezado del documento.
        if ($rowEstudiante = $estudiante->readOne()) {
            $pdf->startReport('Ficha de conducta ' . $rowEstudiante['nombre_estudiante']);
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            
            if ($dataFichas = $fichas->FichasXestudiante()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(154, 201, 229);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(91, 10, $pdf->encodeString('Descripción'), 1, 0, 'C', 1);
                $pdf->cell(60, 10, 'Profesor que asigno', 1, 0, 'C', 1);
                $pdf->cell(35, 10, 'Fecha', 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                // Se recorren los registros fila por fila.
                foreach ($dataFichas as $rowFichas) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(91, 10,  $pdf->encodeString($rowFichas['descripcion_ficha']), 1, 0);
                    $pdf->cell(60, 10,  $pdf->encodeString($rowFichas['nombre_empleado']), 1, 0);
                    $pdf->cell(35, 10,  $pdf->encodeString($rowFichas['fecha_ficha']), 1, 1);
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay fichas para el estudiante'), 1, 1);
            }
            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'Fichas.pdf');
        } else {
            print('Alumno inexistente');
        }
    } else {
        print('Ficha incorrecta');
    }
} else {
    print('Debe seleccionar un Alumno, por favor vuelva a generar el reporte');
}
