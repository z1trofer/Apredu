<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../helpers/report.php');


// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['id_estudiante'])) {
    require_once('../entities/dto/estudiantes.php');
    require_once('../entities/dto/responsables.php');
    // Se instancian las entidades correspondientes.
    $estudiante = new Estudiantes;
    $responsable = new ResponsablesVista;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($estudiante->setIdestudiante($_GET['id_estudiante']) && $responsable->setIdAlumno($_GET['id_estudiante'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        // Se inicia el reporte con el encabezado del documento.
        if ($rowEstudiante = $estudiante->reporteEstudianteOne()) {
            $pdf->startReport('Datos del estudiante: ' . $rowEstudiante['nombre_estudiante']);
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(255, 255, 255);
                //datos del estudiante
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'Nombre', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(150, 10, $pdf->encodeString($rowEstudiante['nombre_estudiante'])." ".$pdf->encodeString($rowEstudiante['apellido_estudiante']), 1, 1, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'F. nacimiento', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(40, 10, $pdf->encodeString($rowEstudiante['fecha_nacimiento']), 1, 0, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'Grado', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(30, 10, $pdf->encodeString($rowEstudiante['grado']), 1, 0, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'NIE', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(20, 10, $pdf->encodeString($rowEstudiante['nie']), 1, 1, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, $pdf->encodeString('Dirección'), 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->MultiCell(150, 10, $pdf->encodeString($rowEstudiante['direccion']), 1, 0, 'C');
                $pdf->Ln(10);
                if($rowsResponsable = $responsable->reportEstudiantesRes()){
                //datos del/los responsable/s
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(180, 10, 'Responsables', 1, 1, 'C', 1);
                foreach ($rowsResponsable as $row) {
                    $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'Nombre', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(150, 10, $pdf->encodeString($row['nombre_responsable'])." ".$pdf->encodeString($row['apellido_responsable']), 1, 1, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'DUI', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(40, 10, $pdf->encodeString($row['dui']), 1, 0, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'Correo', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(80, 10, $pdf->encodeString($row['correo_responsable']), 1, 1, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, $pdf->encodeString('Teléfono'), 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(40, 10, $pdf->encodeString($row['telefono']), 1, 0, 'C', 1);
                $pdf->Ln(10);
                $pdf->Ln(10);
                /*$pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'Grado', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(30, 10, $pdf->encodeString($rowEstudiante['grado']), 1, 0, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, 'NIE', 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->cell(20, 10, $pdf->encodeString($rowEstudiante['nie']), 1, 1, 'C', 1);
                $pdf->setFont('Arial', 'B', 11);
                $pdf->cell(30, 10, $pdf->encodeString('Dirección'), 1, 0, 'C', 1);
                $pdf->setFont('Arial', '', 11);
                $pdf->MultiCell(150, 10, $pdf->encodeString($rowEstudiante['direccion']), 1, 0, 'C');
                $pdf->Ln(10);
                $pdf->setFont('Arial', 'B', 11);*/
                };
                
                }else{
                    $pdf->cell(180, 10, 'No hay responsables registrados', 1, 1, 'C', 1);
                }
                
                // Se imprimen las celdas con los encabezados.
                /*$pdf->cell(91, 10, $pdf->encodeString('Descripción'), 1, 0, 'C', 1);
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
                }*/
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
