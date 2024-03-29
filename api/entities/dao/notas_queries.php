<?php
require_once('../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class NotasQueries
{

    //funcion para obtener todas las materias y grados
    function obtenerMaterias()
    {
        $sql = "SELECT empleados.id_empleado, CONCAT(empleados.nombre_empleado,' ', empleados.apellido_empleado) AS nombre, 
        asignaturas.id_asignatura, asignaturas.asignatura, grados.id_grado, grados.grado FROM detalle_asignaturas_empleados
        LEFT JOIN empleados USING(id_empleado)
        INNER JOIN asignaturas USING(id_asignatura)
        INNER JOIN grados USING (id_grado)
        ";
        if($_SESSION['id_cargo'] == 2){
            $sql = $sql.' where id_empleado = '.$_SESSION['id_empleado'];
        }
        $sql = $sql." order by id_asignatura";
        return Database::getRows($sql);
    }

    //Obtener los trimestres del año lectivo
    function obtenerTrimestres()
    {
        if($_SESSION['id_cargo'] == 2){
            $sql = "SELECT trimestres.id_trimestre, trimestres.trimestre, anios.id_anio, anios.anio, trimestres.estado
            From trimestres INNER JOIN anios USING (id_anio) WHERE estado = true";
        }else{
            $sql = "SELECT trimestres.id_trimestre, trimestres.trimestre, anios.id_anio, anios.anio, trimestres.estado
            From trimestres INNER JOIN anios USING (id_anio) WHERE anios.id_anio = (select id_anio from trimestres where estado = true)";
        }
        //$params = array($anio);
        return Database::getRows($sql);
    }

    //Obtener los trimestres del año lectivo (sin parametro)
    function obtenerTrimestresActual()
    {
        $sql = "SELECT id_trimestre, trimestre, anios.anio from trimestres 
        INNER JOIN anios USING (id_anio)
        where id_anio = (select id_anio from trimestres where estado = true)";
        return Database::getRows($sql);
    }
    //obtiene el anio del trimestre activo
    function ObtenerAnio()
    {
        $sql = "SELECT DISTINCT anios.anio from trimestres 
        INNER JOIN anios USING (id_anio)
        where id_anio = (select id_anio from trimestres where estado = true)";
        return Database::getRow($sql);
    }

    function obtenerGrados()
    {
        $sql = "SELECT id_grado, grado from grados";
        return Database::getRows($sql);
    }

    //Obtener asignaturas de un grado especifico
    function ObtenerAsignaturas($grado)
    {
        $sql = "SELECT id_asignatura, asignatura from asignaturas INNER JOIN detalle_asignaturas_empleados USING (id_asignatura)
        where id_grado = " . $grado;
        return Database::getRows($sql);
    }

    //obtener actividades segun docente, asignatura y trimestre
    function obtenerActividades()
    {
        $sql = "SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad
        from actividades
        INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
        where id_empleado = ? and id_asignatura = ? and id_trimestre = (SELECT id_trimestre FROM trimestres WHERE estado = true)  and id_grado = ?
        order by id_actividad asc";
        $params = array($this->id_empleado, $this->id_asignatura, $this->id_grado);
        return Database::getRows($sql, $params);
    }

    //obtener actividades sin el docente
    function obtenerActividadesDirector()
    {
        $sql = "SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad
        from actividades
        INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
        where id_asignatura = ? and id_trimestre = ? and id_grado = ?
        order by id_actividad asc";
        $params = array($this->id_asignatura, $this->id_trimestre, $this->id_grado);
        return Database::getRows($sql, $params);
    }

    function obtenerActividad()
    {
        $sql = "SELECT  id_nota, ROW_NUMBER() OVER(ORDER BY estudiantes.apellido_estudiante asc) as 'n_lista', estudiantes.apellido_estudiante, estudiantes.nombre_estudiante, actividades.nombre_actividad, actividades.descripcion, actividades.ponderacion, notas.nota from notas 
        INNER JOIN estudiantes USING(id_estudiante) INNER JOIN actividades USING (id_actividad)
        INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
        where id_actividad = ? and detalle_asignaturas_empleados.id_empleado = ? and estudiantes.estado = 1";
        $params = array($this->id_actividad, $this->id_empleado);
        return Database::getRows($sql, $params);
    }

    //obtener notas de una actividad sin el id_empleado
    function obtenerActividadDirector()
    {
        $sql = "SELECT  id_nota, ROW_NUMBER() OVER(ORDER BY estudiantes.apellido_estudiante asc) as 'n_lista', estudiantes.apellido_estudiante, estudiantes.nombre_estudiante, actividades.nombre_actividad, actividades.descripcion, actividades.ponderacion, notas.nota from notas 
        INNER JOIN estudiantes USING(id_estudiante) INNER JOIN actividades USING (id_actividad)
        INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
        where id_actividad = ? and  estudiantes.estado = 1";
        $params = array($this->id_actividad);
        return Database::getRows($sql, $params);
    }

    //actualizarNotas
    function CambiarNotas()
    {
        $sql = "UPDATE notas SET nota = ? where id_nota = ?";
        $params = array($this->nota, $this->id_nota);
        return Database::executeRow($sql, $params);
    }


    //Obtener el promedio total de un trimestre de una materia y alumno especifico
    function obtenerNotaTrimestre()
    {
        $sql = "SELECT ROUND(SUM(valor),2) as puntaje from (select nota*(actividades.ponderacion/100) as valor from notas
        INNER JOIN actividades USING (id_actividad)
        INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
        INNER JOIN asignaturas USING (id_asignatura)
        INNER JOIN grados USING (id_grado)
        INNER JOIN trimestres USING (id_trimestre)
        where id_estudiante = ?
        and trimestres.id_trimestre = ? and asignaturas.id_asignatura = ?)  as consulta
        ";
        $params = array($this->id_estudiante, $this->id_trimestre, $this->id_asignatura);
        return Database::getRow($sql, $params);
    }

    //notas de activididades de todos los estudiantes segun grado
    function NotasDeEstudiantesPorActividades()
    {
        $sql = "SELECT nota, asi.nombre_actividad, asi.asignatura, nombre_estudiante
        FROM notas 
        INNER JOIN (Select actividades.id_actividad, actividades.nombre_actividad, detalle_asignaturas_empleados.id_detalle_asignatura_empleado, detalle_asignaturas_empleados.id_grado, detalle_asignaturas_empleados.id_asignatura , asignaturas.asignatura FROM actividades
                    INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
                    INNER JOIN asignaturas USING(id_asignatura)) as asi USING(id_actividad)
        INNER JOIN estudiantes USING(id_estudiante)
        WHERE asi.id_grado = ?";
        $params = array($this->id_grado);
        return Database::getRows($sql, $params);
    }

    //Obtener los mejores 5 promedios
    function TopNotas($parametros)
    {
        $sql = "SELECT id_estudiante, CONCAT(nombre_estudiante,' ', apellido_estudiante) as nombre, ROUND(AVG(promedio),2) as promedio from
        (select trimestres.id_trimestre, estudiantes.id_estudiante, estudiantes.nombre_estudiante, estudiantes.apellido_estudiante, SUM(valor) as promedio from
        (select trimestres.id_trimestre, asignaturas.asignatura, estudiantes.id_estudiante, notas.nota, ROUND(notas.nota*actividades.ponderacion/100, 2) as valor  from notas
        INNER JOIN actividades USING (id_actividad)
        INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
        INNER JOIN asignaturas USING (id_asignatura)
        INNER JOIN trimestres USING (id_trimestre)
        INNER JOIN anios USING (id_anio)
        INNER JOIN grados USING (id_grado)
        INNER JOIN estudiantes USING (id_estudiante) where anios.anio = (select anio from anios INNER JOIN trimestres USING (id_anio) where trimestres.estado = true) and  estudiantes.estado = 1";
        if ($parametros['grado'] != "Todos") {
            $sql = $sql . " and grados.id_grado = " . $parametros['grado'];
        };
        $sql = $sql . " ) as consulta 
        INNER JOIN trimestres USING (id_trimestre)
        INNER JOIN estudiantes USING (id_estudiante)
        where trimestres.id_trimestre = consulta.id_trimestre and estudiantes.id_estudiante = consulta.id_estudiante and  estudiantes.estado = 1
        GROUP BY trimestres.id_trimestre, consulta.asignatura, estudiantes.id_estudiante
        ORDER BY estudiantes.nombre_estudiante) as wea ";
        if ($parametros['trimestre'] != "Todos") {
            $sql = $sql . "where id_trimestre = " . $parametros['trimestre'];
        };
        $sql = $sql . " GROUP BY id_estudiante
        ORDER BY promedio DESC
        LIMIT 5";
        //$params = array($this->id_grado);
        return Database::getRows($sql);
    }

    public function notaGlobal()
    {
        $sql = "SELECT nombre_estudiante, AVG(nota) as promedio 
        FROM notas
        INNER JOIN estudiantes USING (id_estudiante)
        GROUP BY nombre_estudiante
        ORDER BY promedio DESC LIMIT 5";
        return Database::getRows($sql);
    }

    //consulta para obtener los estudiantes aprobados y reprobados
    function aproYRepro($parametros)
    {
        $sql = "SELECT COUNT(promedio) as cantidad from (SELECT id_estudiante, CONCAT(nombre_estudiante,' ', apellido_estudiante) as nombre, ROUND(AVG(promedio),2) as promedio from
        (select trimestres.id_trimestre, estudiantes.id_estudiante, estudiantes.nombre_estudiante, estudiantes.apellido_estudiante, SUM(valor) as promedio from
        (select trimestres.id_trimestre, asignaturas.asignatura, estudiantes.id_estudiante, notas.nota, ROUND(notas.nota*actividades.ponderacion/100, 2) as valor  from notas
        INNER JOIN actividades USING (id_actividad)
        INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
        INNER JOIN asignaturas USING (id_asignatura)
        INNER JOIN trimestres USING (id_trimestre)
        INNER JOIN anios USING (id_anio)
        INNER JOIN grados USING (id_grado)
        INNER JOIN estudiantes USING (id_estudiante) where anios.anio = (select anio from anios INNER JOIN trimestres USING (id_anio) where trimestres.estado = true) and  estudiantes.estado = 1 ";
        if ($parametros['grado'] != "Todos") {
            $sql = $sql . " and grados.id_grado = " . $parametros['grado'];
        };
        $sql = $sql . " ) as consulta 
    INNER JOIN trimestres USING (id_trimestre)
    INNER JOIN estudiantes USING (id_estudiante)
    where trimestres.id_trimestre = consulta.id_trimestre and estudiantes.id_estudiante = consulta.id_estudiante and  estudiantes.estado = 1
    GROUP BY trimestres.id_trimestre, consulta.asignatura, estudiantes.id_estudiante
    ORDER BY estudiantes.nombre_estudiante) as wea ";
    if ($parametros['trimestre'] != "Todos") {
        $sql = $sql . "where id_trimestre = " . $parametros['trimestre'];
    };
    $sql = $sql . " GROUP BY id_estudiante
    ORDER BY promedio DESC) as xd where promedio ".$parametros['condicion']." 6";
    return Database::getRow($sql);
    }
}
