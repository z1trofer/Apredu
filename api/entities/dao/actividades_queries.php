<?php
require_once('../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class ActividadesQueries
{
    // funcion para leer todos los datos de actividades
    public function readAll()
    {
        if ($_SESSION['tipo'] == 'administrador') {
            $sql = 'SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad, grados.grado, asignaturas.asignatura
        FROM actividades 
        INNER JOIN tipo_actividades USING (id_tipo_actividad)
        INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
        INNER JOIN grados USING (id_grado)
        INNER JOIN asignaturas USING (id_asignatura)
        ORDER BY id_actividad';
            return Database::getRows($sql);
        } else {
            $sql = 'SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad, grados.grado, asignaturas.asignatura
            from actividades
            INNER JOIN tipo_actividades USING (id_tipo_actividad)
            INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
            INNER JOIN grados USING(id_grado)
            INNER JOIN asignaturas USING(id_asignatura)
            INNER JOIN (Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, detalle_asignaturas_empleados.id_grado, detalle_asignaturas_empleados.id_asignatura , asignaturas.asignatura, grados.grado, empleados.id_empleado FROM detalle_asignaturas_empleados
            INNER JOIN grados USING(id_grado)
            INNER JOIN asignaturas USING(id_asignatura)
            INNER JOIN empleados USING (id_empleado)) as asignacion USING(id_detalle_asignatura_empleado)
            where asignacion.id_empleado = ?
            order by id_actividad asc';
            $params = array($_SESSION['id_empleado']);
            return Database::getRows($sql, $params);
        }
    }

    /*funcion para leer datos específicamente*/
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
    // funcion para leer cierto tipo de actividad
    public function readOneTipoActividad()
    {
        $sql = "SELECT id_tipo_actividad from tipo_actividades where id_tipo_actividad = ?";
        $params = array($this->id_tipo_actividad);
        return Database::getRow($sql, $params);
    }

    // funcion para ingresar un nuevo tipo de actividad
    public function addTipoActividad()
    {
        $sql = 'INSERT INTO tipo_actividades (tipo_actividad)
         values (?)';
        $params = array($this->tipo_actividad);
        return Database::executeRow($sql, $params);
    }

    // funcion para actualizar un tipo de actividad
    public function updateTipoActividad()
    {
        $sql = 'UPDATE tipo_actividades SET tipo_actividad=? where id_tipo_actividad = ?';
        $params = array($this->tipo_actividad, $this->id_tipo_actividad);
        return Database::executeRow($sql, $params);
    }

    // funcion para eliminar un tipo de actividad
    public function deleteTipoActividad()
    {
        $sql = 'DELETE FROM tipo_actividades where id_tipo_actividad = ?';
        $params = array($this->id_tipo_actividad);
        return Database::executeRow($sql, $params);
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
        $sql = "SELECT DISTINCT id_grado, grado
            FROM grados LEFT JOIN detalle_asignaturas_empleados USING (id_grado)";
        if ($_SESSION['id_cargo'] == 2) {
            $sql = $sql . " WHERE detalle_asignaturas_empleados.id_empleado = " . $_SESSION['id_empleado'];
        }
        return Database::getRows($sql);
    }

    // funcion paara leer todas las asignaturas
    public function readAsignaturas()
    {
        $sql = "SELECT DISTINCT id_asignatura, asignatura
            FROM asignaturas LEFT JOIN detalle_asignaturas_empleados USING (id_asignatura)";
        if ($_SESSION['id_cargo'] == 2) {
            $sql = $sql . " WHERE detalle_asignaturas_empleados.id_empleado = " . $_SESSION['id_empleado'] . " AND detalle_asignaturas_empleados.id_grado = ?";
        } else {
            $sql = $sql . " WHERE detalle_asignaturas_empleados.id_grado = ?";
        }
        $params = array($this->id_grado);
        return Database::getRows($sql, $params);
    }

    // funcion para leer todo los trimestres por año
    public function readTrimestres()
    {
        $sql = "SELECT id_trimestre, trimestre, anios.anio, estado
        FROM trimestres LEFT JOIN anios using(id_anio) where anio = (select anios.anio from trimestres LEFT JOIN anios USING (id_anio) where trimestres.estado = true)";
        return Database::getRows($sql);
    }

    //Querie obtener el datelle de una actividad (parametro: permiso de usuario view_all_actividades)
    public function readDetalleAsignaturaGrado($level)
    {
        if ($level == true) {
            //si el usuario tiene el permiso podrá ver todas las actividades
            $sql = "Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, concat(asignaturas.asignatura,' de ' ,grados.grado) as asignacion, id_empleado
            FROM detalle_asignaturas_empleados LEFT JOIN empleados USING (id_empleado)
            INNER JOIN asignaturas USING(id_asignatura)
            INNER JOIN grados USING (id_grado)";
            return Database::getRows($sql);
        } else {
            //de lo contrario el usuario solo podrá ver las actividades asignadas a el
            $sql = "Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, concat(asignaturas.asignatura,' de ' ,grados.grado) as asignacion, id_empleado
            FROM detalle_asignaturas_empleados LEFT JOIN empleados USING (id_empleado)
            INNER JOIN asignaturas USING(id_asignatura)
            INNER JOIN grados USING (id_grado)
            WHERE id_empleado = ?";
            //parámetros
            $params = array($_SESSION['id_empleado']);
            return Database::getRows($sql, $params);
        }
    }

    // Para la búsqueda parametrizada
    public function filtrarActividades()
    {
        $sql = "SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, tipo_actividades.tipo_actividad, grados.grado, asignaturas.asignatura
        FROM actividades
        INNER JOIN tipo_actividades USING (id_tipo_actividad)
        INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
        INNER JOIN grados USING (id_grado)
        INNER JOIN asignaturas USING (id_asignatura)
        WHERE id_trimestre = ? and detalle_asignaturas_empleados.id_grado = ? and id_asignatura = ? GROUP BY  actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega
        ORDER BY actividades.nombre_actividad ASC";
        $params = array($this->id_trimestre, $this->id_grado, $this->id_asignatura);
        return Database::getRows($sql, $params);
    }


    // Ingresar una nueva actividad
    public function createRow()
    {
        $sql = 'INSERT INTO actividades (nombre_actividad, ponderacion, descripcion, fecha_entrega, id_detalle_asignatura_empleado, id_tipo_actividad, id_trimestre)
    VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_actividad, $this->ponderacion, $this->descripcion, $this->fecha_entrega, $this->id_detalle_asignatura_empleado, $this->id_tipo_actividad, $this->id_trimestre);
        return Database::executeRow($sql, $params);
    }

    //validar ponderación
    public function validatePonderacion($modo)
    {
        if ($modo == true) {
            $sql = "SELECT actividades.id_actividad, actividades.ponderacion, actividades.id_trimestre, grados.id_grado, asignaturas.id_asignatura FROM actividades INNER JOIN tipo_actividades USING (id_tipo_actividad)
            INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
            INNER JOIN grados USING (id_grado)
            INNER JOIN asignaturas USING (id_asignatura) INNER JOIN ( SELECT actividades.id_actividad, actividades.ponderacion, actividades.id_trimestre, grados.id_grado, asignaturas.id_asignatura
            FROM actividades
            INNER JOIN tipo_actividades USING (id_tipo_actividad)
            INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
            INNER JOIN grados USING (id_grado)
            INNER JOIN asignaturas USING (id_asignatura)
            WHERE id_actividad = ?  GROUP BY actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega
            ORDER BY actividades.nombre_actividad ASC) as actiparam 
            where actividades.id_trimestre = actiparam.id_trimestre and grados.id_grado = actiparam.id_grado and asignaturas.id_asignatura = actiparam.id_asignatura and actividades.id_actividad != actiparam.id_actividad";
            $params = array($this->id_actividad);
            $data = Database::getRows($sql, $params);
        } else {
            $sql = "SELECT actividades.id_actividad, actividades.nombre_actividad, actividades.ponderacion,  grados.grado, asignaturas.asignatura
            FROM actividades
            INNER JOIN tipo_actividades USING (id_tipo_actividad)
            INNER JOIN detalle_asignaturas_empleados USING(id_detalle_asignatura_empleado)
            INNER JOIN grados USING (id_grado)
            INNER JOIN asignaturas USING (id_asignatura)
            WHERE actividades.id_detalle_asignatura_empleado = ? and id_trimestre = ?";
            $params = array($this->id_detalle_asignatura_empleado, $this->id_trimestre);
            $data = Database::getRows($sql, $params);
        }
        $suma = $this->ponderacion;
        foreach ($data as $datarow) {
            $suma = $suma + $datarow['ponderacion'];
        }
        if ($suma > 100) {
            return false;
        } else {
            return true;
        }
    }

    //actualizar actividad
    public function updateRow()
    {
        $sql = 'UPDATE actividades SET nombre_actividad = ?, ponderacion = ?, id_tipo_actividad = ?, descripcion = ?, fecha_entrega = ? WHERE id_actividad= ? ';
        $params = array($this->nombre_actividad, $this->ponderacion, $this->id_tipo_actividad, $this->descripcion, $this->fecha_entrega, $this->id_actividad);
        return Database::executeRow($sql, $params);
    }

    // eliminar actividad
    public function deleteRow()
    {
        $sql = 'DELETE FROM actividades
                WHERE id_actividad = ?';
        $params = array($this->id_actividad);
        return Database::executeRow($sql, $params);
    }
}
