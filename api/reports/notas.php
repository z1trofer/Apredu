<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../helpers/report_notas.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../entities/dto/notas.php');
require_once('../entities/dto/estudiantes.php');
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('BOLETA DE NOTAS');
//$pdf->cell(20, 10, '2023', 1, 0);
// Se instancia las clases necesarias
$estudiantes = new Estudiantes;
$notas = new Notas;
if ($anio = $notas->ObtenerAnio()) {
    $pdf->cell(0, 12,  $pdf->encodeString("AÑO ") . $anio['anio'], 0, 1, "C");
} else {
    $pdf->cell(0, 10, 'error', 1, 1);
}
$pdf->setFont('Arial', '', 10);
//se verifica que hay parametros en la url
if (isset($_GET['id'])) {
    //se setean los parametros requeridos
    if ($estudiantes->setIdEstudiante($_GET['id']) && $notas->setId_estudiante($_GET['id'])) {
        //se va a buscar la información del estudiante
        if ($dataEstudiante = $estudiantes->ReadOne()) {
            //se aniade la informacion del estudiante al reporte
            $pdf->setFont('Arial', 'B', 10);
            $pdf->cell(20, 10, 'Estudiante:', 0, 0);
            $pdf->setFont('Arial', '', 10);
            $pdf->cell(120, 10, $pdf->encodeString($dataEstudiante["nombre_estudiante"] . " " . $dataEstudiante["apellido_estudiante"]), 0, 0);
            $pdf->setFont('Arial', 'B', 10);
            $pdf->cell(15, 10, 'Grado:', 0, 0);
            $pdf->setFont('Arial', '', 10);
            $pdf->cell(40, 10, $dataEstudiante["grado"], 0, 0);
            $pdf->setFont('Arial', 'B', 10);
            $pdf->cell(15, 10, 'NIE:', 0, 0);
            $pdf->setFont('Arial', '', 10);
            $pdf->cell(40, 10, $dataEstudiante["nie"], 0, 0);
            $pdf->ln(15);
            if ($dataAsignaturas = $notas->ObtenerAsignaturas($dataEstudiante["id_grado"])) {
                //se buscan las asignaturas correspondientes al grado del estudiante
                $pdf->setFont('Arial', 'B', 10);
                $pdf->setFillColor(115, 120, 117);
                //se llenan los encabezados de la talba
                $pdf->cell(50, 10, 'Asignaturas', "B", 0, 'C');
                $pdf->cell(50, 10, 'Primer Trimestre', "L, B, R", 0, 'C');
                $pdf->cell(50, 10, 'Segundo Trimestre', 'B, R', 0, 'C');
                $pdf->cell(50, 10, 'Tercer Trimestre', 'B, R', 0, 'C');
                $pdf->cell(50, 10, 'Nota Final', 'B', 1, 'C');
                $pdf->setFont('Arial', '', 10);
                //se recorren las asignaturas segun los trimestres
                if ($dataTrimestre = $notas->ObtenerTrimestresActual()) {
                    //por cada asignatura se crea una nueva fila
                    foreach ($dataAsignaturas as $rowAsignaturas) {
                        $notas->setId_asignatura($rowAsignaturas['id_asignatura']);
                        $pdf->cell(50, 10, $pdf->encodeString($rowAsignaturas['asignatura']), 'R', 0, 'C');
                        $valores = array();
                        foreach ($dataTrimestre as $rowTrimestre) {
                            //por cada trimestre se crea una nueva columna
                            $notas->setId_trimestre($rowTrimestre['id_trimestre']);
                            if ($data = $notas->obtenerNotaTrimestre()) {
                                if ($data['puntaje'] != null) {
                                    $pdf->cell(50, 10, $data['puntaje'], 'R', 0, 'C');
                                    array_push($valores, $data['puntaje']);
                                } else {
                                    $pdf->cell(50, 10, "", 'R', 0, 'C');
                                }
                            } else {
                                $pdf->cell(50, 10, "error", 1, 0, 'C');
                            }
                        }
                        //contador de la cantidad de columnas para saber cuando insertar la fila de total
                        if (count($valores) == 3) {
                            //se consigue el promedio de las notas obtenidas para sacar un promedio total
                            $suma = array_sum($valores) / 3;
                            $pdf->cell(50, 10, number_format($suma, 2), 'L', 1, 'C');
                        } else {
                            $pdf->cell(50, 10, " ", 0, 1, 'C');
                        }
                    }
                    //se aniaden mas campos de informacion
                    $pdf->ln(10);
                    $pdf->cell(115, 5, 'Observaciones:', 0, 0, '');
                    $pdf->cell(80, 5, 'Firma Director', 0, 1, '');
                    $pdf->cell(100, 20, '', 1, 0, '');
                    $pdf->cell(80, 20, '________________________', 0, 0, 'C');
                    //$pdf->cell(50, 10, 'Firma', 1, 0, '');
                }
            } else {
                //se envia un error respectivo
                $pdf->cell(0, 10, $pdf->encodeString('Error con los trimestres'), 1, 1);
            }
        } else {
            //se envia un error respectivo
            $pdf->cell(0, 10, $pdf->encodeString('Error con el estudiante'), 1, 1);
        }
    }
} else {
    //se envia un error respectivo
    $pdf->cell(0, 10, $pdf->encodeString('Error con los parametros, por favor genere el documento nuevamente'), 1, 1);
}

$pdf->output('I', 'Reporte del personal académico.pdf');
