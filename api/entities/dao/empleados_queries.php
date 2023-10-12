<?php
require_once('../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class EmpleadosQueries
{

    // funcion para leer todos los empleados
    public function readAll($check)
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado
        FROM empleados 
        INNER JOIN cargos_empleados USING (id_cargo)';
        if ($check == "true") {
            $sql = $sql . ' where id_cargo = 2 ORDER BY id_empleado';
        } else {
            $sql = $sql . ' where id_cargo != 2 ORDER BY id_empleado';
        };
        return Database::getRows($sql);
    }

    /*funcion para leer datos específicos*/
    public function readOne()
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.telefono, empleados.direccion, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado, cargos_empleados.id_cargo, empleados.estado, empleados.intentos
        FROM empleados 
        INNER JOIN cargos_empleados USING (id_cargo)
        Where id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::getRow($sql, $params);
    }

    // Para cargar combobox
    public function readCargos()
    {
        $sql = 'SELECT id_cargo, cargo FROM cargos_empleados';
        return Database::getRows($sql);
    }

    // funcion para crear un empleado
    public function createRow()
    {
        $sql = 'INSERT INTO empleados (nombre_empleado, apellido_empleado, dui, fecha_nacimiento, id_cargo, usuario_empleado, direccion, clave, telefono, correo_empleado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario_empleado, $this->direccion, $this->clave, $this->telefono, $this->correo_empleado);
        return Database::executeRow($sql, $params);
    }
    // funcion para actualizar un empleado
    public function updateRow()
    {
        $sql = 'UPDATE empleados SET nombre_empleado = ?, apellido_empleado = ?, dui = ?, fecha_nacimiento = ?, id_cargo = ?, usuario_empleado = ?, direccion = ?, telefono = ?, correo_empleado = ?, estado = ? WHERE id_empleado= ? ';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario_empleado, $this->direccion, $this->telefono, $this->correo_empleado, $this->estado, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    // funcion para cambiar la contraseña de cierto empleado
    public function changePassword()
    {
        $sql = 'UPDATE empleados
                SET clave = ?, fecha_clave = current_timestamp()	
                WHERE id_empleado = ?';
        $params = array($this->clave, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    // funcion para resetear intentos
    public function resetIntentos()
    {
        $sql = 'UPDATE empleados set intentos = 0 where usuario_empleado = ?';
        $params = array($this->usuario_empleado);
        return Database::executeRow($sql, $params);
    }

    // funcion para eliminar un empleado
    public function deleteRow()
    {
        $sql = 'DELETE FROM empleados
                WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    // funcion para buscar empleados específicos
    public function searchRows($value, $check)
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado
        FROM empleados
        INNER JOIN cargos_empleados USING (id_cargo)
        WHERE apellido_empleado LIKE ? OR nombre_empleado LIKE ?';
        if ($check == false) {
            $sql = $sql . ' and id_cargo = 2 ORDER BY id_empleado';
        } else {
            $sql = $sql . ' and id_cargo != 2 ORDER BY id_empleado';
        };
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    // llenar combobox de actividades
    function obtenerActividades()
    {
        $sql = "SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad, ponderacion, descripcion, fecha_entrega, asignacion.grado, asignacion.asignatura
        from actividades
        INNER JOIN (Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, detalle_asignaturas_empleados.id_grado, detalle_asignaturas_empleados.id_asignatura , asignaturas.asignatura, grados.grado, empleados.id_empleado FROM detalle_asignaturas_empleados
				   INNER JOIN grados USING(id_grado)
				   INNER JOIN asignaturas USING(id_asignatura)
                   INNER JOIN empleados USING (id_empleado)) as asignacion USING(id_detalle_asignatura_empleado)
        where id_empleado = ? and  id_asignatura = ? and id_grado = ?
        order by id_actividad asc";
        $params = array($this->id_empleado, $this->id_asignatura, $this->id_grado);
        return Database::getRows($sql, $params);
    }

    // funcion para leer todo sin filtro 
    function readSinFiltros()
    {
        $sql = "SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad, ponderacion, descripcion, fecha_entrega, asignacion.grado, asignacion.asignatura
        from actividades
        INNER JOIN (Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado, detalle_asignaturas_empleados.id_grado, detalle_asignaturas_empleados.id_asignatura , asignaturas.asignatura, grados.grado, empleados.id_empleado FROM detalle_asignaturas_empleados
				   INNER JOIN grados USING(id_grado)
				   INNER JOIN asignaturas USING(id_asignatura)
				   INNER JOIN empleados USING (id_empleado)) as asignacion USING(id_detalle_asignatura_empleado)		   
        where id_empleado = ?
        order by id_actividad asc";
        $params = array($this->id_empleado);
        return Database::getRows($sql, $params);
    }

    // funcion para leer asignaturas de cierto empleado
    public function readAsignaturasEmpleado()
    {
        $sql = 'SELECT a.id_asignatura, b.asignatura FROM detalle_asignaturas_empleados a
        INNER JOIN asignaturas b USING(id_asignatura)
        INNER JOIN empleados c USING(id_empleado)
        where c.id_empleado = ?
		GROUP BY id_asignatura, asignatura';
        $params = array($this->id_empleado);
        return Database::getRows($sql, $params);
    }

    // funcion para leer datos de asignaturas
    public function readAsignaturas()
    {
        $sql = 'SELECT a.id_asignatura, b.asignatura FROM detalle_asignaturas_empleados a
        INNER JOIN asignaturas b USING(id_asignatura)
        INNER JOIN empleados c USING(id_empleado)
		GROUP BY id_asignatura, asignatura';
        return Database::getRows($sql);
    }

    // funcion de leer datos de asignaturas por grado
    public function readAsignaturasGrado($id)
    {
        $sql = 'SELECT id_asignatura, asignaturas.asignatura FROM detalle_asignaturas_empleados
        INNER JOIN asignaturas USING (id_asignatura)
            where id_grado = ?';
        $params = array($id);
        return Database::getRows($sql, $params);
    }

    // funcion para leer datos de grados por cierto empleado
    public function readGradosEmpleado()
    {
        $sql = 'SELECT a.id_grado, b.grado FROM detalle_asignaturas_empleados a
        INNER JOIN grados b USING(id_grado)
        INNER JOIN empleados c USING(id_empleado)
        where c.id_empleado = ?
        GROUP BY id_grado, grado';
        $params = array($this->id_empleado);
        return Database::getRows($sql, $params);
    }

    // funcion para leer datos datos de grados
    public function readGrados()
    {
        $sql = 'SELECT a.id_grado, b.grado FROM detalle_asignaturas_empleados a
        LEFT JOIN grados b USING(id_grado)
        LEFT JOIN empleados c USING(id_empleado)
        GROUP BY id_grado, grado ORDER BY "id_grado" ASC';
        return Database::getRows($sql);
    }

    //funcion cargar los detalles de los empleado. parametro: id del empleado
    public function CargarDetalles($id)
    {
        $sql = "SELECT id_detalle_asignatura_empleado, id_empleado, grados.grado, asignaturas.asignatura FROM detalle_asignaturas_empleados
        INNER JOIN grados USING (id_grado)
        INNER JOIN asignaturas USING (id_asignatura)
        WHERE id_empleado = ?";
        $params = array($id);
        return Database::getRows($sql, $params);
    }

    //funcion actualizar detalle
    public function actualizarDetalle($asignatura, $grado)
    {
        $sql = 'UPDATE detalle_asignaturas_empleados SET id_empleado = ? 
        where id_asignatura = ? and id_grado = ?';
        $params = array($this->id_empleado, $asignatura, $grado);
        return Database::executeRow($sql, $params);
    }


    //funcion verificar detalle
    public function verificarDetalle($asignatura, $grado)
    {
        $sql = 'SELECT id_detalle_asignatura_empleado, id_empleado, id_asignatura, id_grado from detalle_asignaturas_empleados
        where id_asignatura = ? and id_grado = ?';
        $params = array($asignatura, $grado);
        return Database::getRow($sql, $params);
    }

    //eliminar detalle
    public function deleteDetalle()
    {
        $sql = 'UPDATE detalle_asignaturas_empleados SET id_empleado = null 
        where id_detalle_asignatura_empleado = ?';
        //se usa el id empleado para validar el id
        $params = array($this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    // funcion para leer datos de empleados por cargo
    public function readPorCargos()
    {
        $sql = 'SELECT nombre_empleado, apellido_empleado, correo_empleado, cargo, dui
        FROM empleados INNER JOIN cargos_empleados USING(id_cargo)
        WHERE id_cargo = ?
        ORDER BY nombre_empleado';
        $params = array($this->id_cargo);
        return Database::getRows($sql, $params);
    }

    // funcion para leer empleados por asignatura 
    public function AsignaturaEmpleadoGrado()
    {
        $sql = 'SELECT nombre_empleado, apellido_empleado, grado, asignatura, cargo
        FROM detalle_asignaturas_empleados 
        INNER JOIN empleados USING(id_empleado)
        INNER JOIN asignaturas asi USING(id_asignatura)
        INNER JOIN grados USING(id_grado)
        INNER JOIN cargos_empleados USING(id_cargo)
        WHERE asi.id_asignatura = ?
        GROUP BY nombre_empleado, cargo
        ORDER BY grado DESC';
        $params = array($this->id_asignatura);
        return Database::getRows($sql, $params);
    }

    // funcion para obtenener asignaturas por el grado
    public function gradoAsignaturas()
    {
        $sql = 'SELECT a.id_asignatura, a.asignatura
        FROM asignaturas a
        INNER JOIN detalle_asignaturas_empleados dae USING(id_asignatura)
        INNER JOIN grados g USING(id_grado)
        WHERE g.id_grado = ?
        ORDER BY asignatura ASC';
        $params = array($this->id_grado);
        return Database::getRows($sql, $params);
    }
    

    // funcion para obtener el correo del empleado en sesion
    public function ObtenerCorreo()
    {
        $sql = 'SELECT correo_empleado FROM empleados WHERE id_empleado = ?';
        $params = array($_SESSION['id_empleado']);
        return Database::getRows($sql, $params);
    }
}
