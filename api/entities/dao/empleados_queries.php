<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class EmpleadosQueries
{
    public function readAll()
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado
        FROM empleados 
        INNER JOIN cargos_empleados USING (id_cargo)
        ORDER BY id_empleado';
        return Database::getRows($sql);
    }

    /*funcion para leer datos*/
    public function readOne()
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.direccion, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado
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
        $sql = 'UPDATE empleados SET nombre_empleado = ?, apellido_empleado = ?, dui = ?, fecha_nacimiento = ?, id_cargo = ?, usuario_empleado = ?, direccion = ?, clave = ?, correo_empleado = ? WHERE id_empleado= ? ';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario_empleado, $this->direccion, $this->clave, $this->correo_empleado, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM empleados
                WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    public function searchRows($value)
    {
        $sql = 'SELECT empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.direccion, empleados.clave, empleados.correo_empleado
        FROM empleados
        INNER JOIN cargos_empleados USING (id_cargo)
        WHERE nombre_empleado LIKE ? OR apellido_empleado LIKE ?
        ORDER BY nombre_empleado';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    function ObtenerActividades() {
        $sql = "SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad, ponderacion, fecha_entrega, asignacion.grado, asignacion.asignatura
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

    function readSinFiltros() {
        $sql = "SELECT id_detalle_asignatura_empleado, id_actividad, nombre_actividad, ponderacion, fecha_entrega, asignacion.grado, asignacion.asignatura
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
}
