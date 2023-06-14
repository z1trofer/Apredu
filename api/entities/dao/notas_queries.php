<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class NotasQueries
{
    //función para obtener las materias y grados que imparte un docente
    function ObtenerMateriasDocente() {
        $sql = "SELECT empleados.id_empleado, CONCAT(empleados.nombre_empleado,' ', empleados.apellido_empleado) AS nombre, 
        asignaturas.id_asignatura, asignaturas.asignatura, grados.id_grado, grados.grado FROM detalle_asignaturas_empleados
        INNER JOIN empleados USING(id_empleado)
        INNER JOIN asignaturas USING(id_asignatura)
        INNER JOIN grados USING (id_grado)
        where id_empleado = ? order by id_asignatura";
        $params = array($this->id_empleado);
        return Database::getRows($sql, $params);
    }

    //Obtener los trimestres del año lectivo
    function ObtenerTrimestres($anio) {
        $sql = "SELECT trimestres.id_trimestre, trimestres.trimestre, anios.id_anio, anios.anio, trimestres.estado
        From trimestres INNER JOIN anios USING (id_anio) WHERE anios.anio = ?";
        $params = array($anio);
        return Database::getRows($sql, $params);
    }

    //obtener actividades segun docente, asignatura y trimestre
    function ObtenerActividades() {
        $sql = "SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad
        from actividades
        INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
        where id_empleado = ? and id_asignatura = ? and id_trimestre = ? and id_grado = ?
        order by id_actividad asc";
        $params = array($this->id_empleado, $this->id_asignatura, $this->id_trimestre, $this->id_grado);
        return Database::getRows($sql, $params);
    }

    function ObtenerActividad() {
        $sql = "SELECT  id_nota, ROW_NUMBER() OVER(ORDER BY estudiantes.apellido_estudiante asc) as 'n_lista', estudiantes.apellido_estudiante, estudiantes.nombre_estudiante, actividades.nombre_actividad, actividades.descripcion, actividades.ponderacion, notas.nota from notas 
        INNER JOIN estudiantes USING(id_estudiante) INNER JOIN actividades USING (id_actividad)
        INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
        where id_actividad = ? and detalle_asignaturas_empleados.id_empleado = ?";
        $params = array($this->id_actividad, $this->id_empleado);
        return Database::getRows($sql, $params);
    }

    function CambiarNotas($nota) {
        $sql = "UPDATE notas SET nota = ? where id_nota = ?";
        $params = array($nota, $this->id_nota);
        return Database::executeRow($sql, $params);
    }


}
