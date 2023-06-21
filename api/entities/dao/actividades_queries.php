<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class ActividadesQueries
{
    public function readAll()
    {
        $sql = 'SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad
        FROM actividades 
        INNER JOIN tipo_actividades USING (id_tipo_actividad)
        ORDER BY id_actividad';
        return Database::getRows($sql);
    }

    /*funcion para leer datos*/
    public function readOne()
    {
        $sql = "SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad, detalle_asignaturas_empleados.id_detalle_asignatura_empleado,
		consulta.asignacion, trimestres.trimestre
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
        $sql = "Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, concat(asignaturas.asignatura,' de ' ,grados.grado) as asignacion
        FROM detalle_asignaturas_empleados LEFT JOIN empleados USING (id_empleado)
        INNER JOIN asignaturas USING(id_asignatura)
        INNER JOIN grados USING (id_grado)";
        return Database::getRows($sql);
    }

    // Para la búsqueda parametrizada
    public function FiltrarActividades($filtros)
    {
        $sql = null;
        $first = false;
        //verificar si se estan filtrando los grados
        if($filtros['trimestre'] != 'Trimestres')
        {
            $sql = 'SELECT actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, trimestres.trimestre
            from actividades
            INNER JOIN trimestres using(id_trimestre)
            WHERE actividades.id_trimestre = '.$filtros['trimestre'].' ORDER BY';
            //si no es el caso se manda a llamar una consulta mas simple
        }else{
            $sql = 'SELECT actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, trimestres.trimestre
            from actividades
            INNER JOIN trimestres using(id_trimestre) ORDER BY
             ';
        };
         //revisa los demas parametros
         if($filtros['grado'] != 'Grados')
         {
             if($first == false)
             {
                 $sql = $sql.' id_grado = '.$filtros['grado'];
                 $first = true;
             }else{
                 $sql = $sql.' AND id_grado = '.$filtros['grado'];
 
             }
             
         };
         if($filtros['asignatura'] != 'Asignaturas')
         {
             if($first == false)
             {
                 $sql = $sql.'id_asignatura = '.$filtros['asignatura'];
                 $first = true;
             }else{
                 $sql = $sql.' AND id_asignatura = '.$filtros['asignatura'];
 
             }
         };

         $sql = $sql.' GROUP BY  actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega
         ORDER BY actividades.nombre_actividad ASC';
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
