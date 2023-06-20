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

    public function CargarPorDetalleAsigGrado($value){
         $sql = "SELECT actividades.nombre_actividad, actividades.ponderacion, actividades.descripcion, actividades.fecha_entrega, detalle_asignaturas_empleados.id_detalle_asignatura_empleado, asignacion FROM actividades INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado) INNER JOIN trimestres USING (id_trimestre) INNER JOIN (Select detalle_asignaturas_empleados.id_detalle_asignatura_empleado,concat(asignaturas.asignatura,' de ' ,grados.grado)  as asignacion FROM detalle_asignaturas_empleados LEFT JOIN empleados USING (id_empleado) INNER JOIN asignaturas USING(id_asignatura) INNER JOIN grados USING (id_grado)) as consulta using (id_detalle_asignatura_empleado) WHERE asignacion LIKE 'Matemáticas de Cuarto grado';";
        $params = array("%$value%");
        return Database::getRows($sql, $params);

    }

    public function readAsignaturas_empleado()
    {
        $sql = 'SELECT id_asignatura, asignatura FROM detalle_asignaturas_empleados
        INNER JOIN asignaturas USING(id_asignatura)
         where id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::getRows($sql);
    }

    public function readGrados_empleado()
    {
        $sql = 'SELECT id_grado, grado FROM detalle_asignaturas_empleados
        INNER JOIN grados USING(id_grado)
         where id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::getRows($sql);
    }
}
