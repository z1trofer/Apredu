<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class ActividadesQueries
{
    public function readAll()
    {
       if ( $_SESSION['tipo'] == 'admin') {
        $sql ='SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad
        FROM actividades 
        INNER JOIN tipo_actividades USING (id_tipo_actividad)
        ORDER BY id_actividad';
        return Database::getRows($sql);

       }else{
        $sql = 'SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad, ponderacion, descripcion, tipo_actividades.tipo_actividad, fecha_entrega, asignacion.grado, asignacion.asignatura
        from actividades
        INNER JOIN tipo_actividades USING (id_tipo_actividad)
        INNER JOIN (Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, detalle_asignaturas_empleados.id_grado, detalle_asignaturas_empleados.id_asignatura , asignaturas.asignatura, grados.grado, empleados.id_empleado FROM detalle_asignaturas_empleados
				   INNER JOIN grados USING(id_grado)
				   INNER JOIN asignaturas USING(id_asignatura)
                   INNER JOIN empleados USING (id_empleado)) as asignacion USING(id_detalle_asignatura_empleado)
        where id_empleado = ?
        order by id_actividad asc';
        $params = array($_SESSION['id_empleado']);
        
        return Database::getRows($sql, $params);
       }
        
    }

    /*funcion para leer datos*/
    public function readOne()
    {
        $sql = "SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad, detalle_asignaturas_empleados.id_detalle_asignatura_empleado,
		consulta.asignacion, detalle_asignaturas_empleados.id_detalle_asignatura_empleado, trimestres.trimestre, trimestres.id_trimestre, tipo_actividades.id_tipo_actividad
           FROM actividades 
           INNER JOIN tipo_actividades USING (id_tipo_actividad)
		              INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
					  INNER JOIN trimestres USING (id_trimestre)
					  INNER JOIN (Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, concat(asignaturas.asignatura,' de ' ,grados.grado) as asignacion
        FROM detalle_asignaturas_empleados LEFT JOIN empleados USING (id_empleado)
        INNER JOIN asignaturas USING(id_asignatura)
        INNER JOIN grados USING (id_grado)) as consulta using (id_detalle_asignatura_empleado)
                   WHERE id_actividad = ?";
        $params = array($this->id_actividad);
        return Database::getRow($sql, $params);
    }

    // Para cargar combobox
    public function readTipoActividades()
    {
        $sql = 'SELECT id_tipo_actividad, tipo_actividad FROM tipo_actividades';
        return Database::getRows($sql);
    }

    // Para cargar combobox
    public function readGrados()
    {
        $sql = "SELECT id_grado, grado
            FROM grados";
        return Database::getRows($sql);
    }

    public function readAsignaturas()
    {
        $sql = "SELECT id_asignatura, asignatura
            FROM asignaturas";
        return Database::getRows($sql);
    }

    public function readTrimestres()
    {
        $sql = "SELECT id_trimestre, trimestre, anios.anio
            FROM trimestres LEFT JOIN anios using(id_anio) where anio ='2023'";
        return Database::getRows($sql);
    }

    // Para cargar combobox
    public function readDetalle_asignatura_grado()
    {
        if ( $_SESSION['tipo'] == 'admin') {
            $sql ="Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, concat(asignaturas.asignatura,' de ' ,grados.grado) as asignacion, id_empleado
            FROM detalle_asignaturas_empleados LEFT JOIN empleados USING (id_empleado)
            INNER JOIN asignaturas USING(id_asignatura)
            INNER JOIN grados USING (id_grado)";
            return Database::getRows($sql);
    
           }else{
            $sql = "Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, concat(asignaturas.asignatura,' de ' ,grados.grado) as asignacion, id_empleado
            FROM detalle_asignaturas_empleados LEFT JOIN empleados USING (id_empleado)
            INNER JOIN asignaturas USING(id_asignatura)
            INNER JOIN grados USING (id_grado)
            WHERE id_empleado = ?";
            $params = array($_SESSION['id_empleado']);
            return Database::getRows($sql, $params);
           } 
    }

    // Para la búsqueda parametrizada
    public function FiltrarActividades($filtros)
    {
        $sql = "SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad
        FROM actividades 
        INNER JOIN tipo_actividades USING (id_tipo_actividad)
        INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
        WHERE id_trimestre = ".$filtros['trimestre']." and detalle_asignaturas_empleados.id_grado = ".$filtros['grado']." and id_asignatura = ".$filtros['asignatura'].
        " GROUP BY  actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega
        ORDER BY actividades.nombre_actividad ASC";
         return Database::getRows($sql);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO actividades (nombre_actividad, ponderacion, descripcion, fecha_entrega, id_detalle_asignatura_empleado, id_tipo_actividad, id_trimestre)
    VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_actividad, $this->ponderacion, $this->descripcion, $this->fecha_entrega, $this->id_detalle_asignatura_empleado, $this->id_tipo_actividad, $this->id_trimestre);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE actividades SET nombre_actividad = ?, ponderacion = ?, id_tipo_actividad = ?, descripcion = ?, fecha_entrega = ?, id_detalle_asignatura_empleado = ?, id_trimestre = ? WHERE id_actividad= ? ';
        $params = array($this->nombre_actividad, $this->ponderacion, $this->id_tipo_actividad, $this->descripcion, $this->fecha_entrega, $this->id_detalle_asignatura_empleado, $this->id_trimestre, $this->id_actividad );
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM actividades
                WHERE id_actividad = ?';
        $params = array($this->id_actividad);
        return Database::executeRow($sql, $params);
    }
}
