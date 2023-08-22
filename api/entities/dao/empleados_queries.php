<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class EmpleadosQueries
{
    public function readAll($check)
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado
        FROM empleados 
        INNER JOIN cargos_empleados USING (id_cargo)';
        if ($check == "false") {
            $sql = $sql . ' where id_cargo = 2 ORDER BY id_empleado';
        } else {
            $sql = $sql . ' where id_cargo != 2 ORDER BY id_empleado';
        };
        return Database::getRows($sql);
    }

    /*funcion para leer datos*/
    public function readOne()
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.direccion, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado, cargos_empleados.id_cargo, empleados.estado
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


    public function createRow()
    {
        $sql = 'INSERT INTO empleados (nombre_empleado, apellido_empleado, dui, fecha_nacimiento, id_cargo, usuario_empleado, direccion, clave, correo_empleado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario_empleado, $this->direccion, $this->clave, $this->correo_empleado);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE empleados SET nombre_empleado = ?, apellido_empleado = ?, dui = ?, fecha_nacimiento = ?, id_cargo = ?, usuario_empleado = ?, direccion = ?, correo_empleado = ?, estado = ? WHERE id_empleado= ? ';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario_empleado, $this->direccion, $this->correo_empleado, $this->estado, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM empleados
                WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::executeRow($sql, $params);
    }

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

    function ObtenerActividades()
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


    public function readAsignaturas_empleado()
    {
        $sql = 'SELECT a.id_asignatura, b.asignatura FROM detalle_asignaturas_empleados a
        INNER JOIN asignaturas b USING(id_asignatura)
        INNER JOIN empleados c USING(id_empleado)
        where c.id_empleado = ?
		GROUP BY id_asignatura, asignatura';
        $params = array($this->id_empleado);
        return Database::getRows($sql, $params);
    }

    public function readAsignaturas()
    {
        $sql = 'SELECT a.id_asignatura, b.asignatura FROM detalle_asignaturas_empleados a
        INNER JOIN asignaturas b USING(id_asignatura)
        INNER JOIN empleados c USING(id_empleado)
		GROUP BY id_asignatura, asignatura';
        return Database::getRows($sql);
    }

    public function readAsignaturasGrado($id)
    {
        $sql = 'SELECT id_asignatura, asignaturas.asignatura FROM detalle_asignaturas_empleados
        INNER JOIN asignaturas USING (id_asignatura)
            where id_grado = ?';
        $params = array($id);
        return Database::getRows($sql, $params);
    }

    public function readGrados_empleado()
    {
        $sql = 'SELECT a.id_grado, b.grado FROM detalle_asignaturas_empleados a
        INNER JOIN grados b USING(id_grado)
        INNER JOIN empleados c USING(id_empleado)
        where c.id_empleado = ?
        GROUP BY id_grado, grado';
        $params = array($this->id_empleado);
        return Database::getRows($sql, $params);
    }

    public function readGrados()
    {
        $sql = 'SELECT a.id_grado, b.grado FROM detalle_asignaturas_empleados a
        LEFT JOIN grados b USING(id_grado)
        LEFT JOIN empleados c USING(id_empleado)
        GROUP BY id_grado, grado';
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
    public function ActualizarDetalle($asignatura, $grado)
    {
        $sql = 'UPDATE detalle_asignaturas_empleados SET id_empleado = ? 
        where id_asignatura = ? and id_grado = ?';
        $params = array($this->id_empleado, $asignatura, $grado);
        return Database::executeRow($sql, $params);
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

    public function readPorCargos()
    {
        $sql = 'SELECT nombre_empleado, apellido_empleado, correo_empleado, cargo, dui
        FROM empleados INNER JOIN cargos_empleados USING(id_cargo)
        WHERE id_cargo = ?
        ORDER BY nombre_empleado';
        $params = array($this->id_cargo);
        return Database::getRows($sql, $params);
    }

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
}?>
