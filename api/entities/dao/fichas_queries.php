<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class FichasQueries
{
    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function readAll()
    {
        $sql ='SELECT id_ficha, id_estudiante, descripcion_ficha, fecha_ficha, id_empleado
                FROM fichas
                INNER JOIN estudiantes USING(id_estudiante)
                order by id_estudiante ASC';
        return Database::getRows($sql);
    }

    /*funcion para leer datos*/
    public function readOne()
    {
        $sql = 'SELECT id_ficha, id_estudiante, descripcion_ficha, fecha_ficha, id_empleado
        FROM fichas 
        INNER JOIN estudiantes USING (id_estudiante)
        INNER JOIN empleados USING (id_empleado)
        Where id_ficha = ?';
        $params = array($this->id_ficha);
        return Database::getRow($sql, $params);
    }

    // Para cargar combobox
    public function readEstudiante()
    {
        $sql = 'SELECT id_estudiante, nombre FROM estudiantes';
        return Database::getRows($sql);
    }

    // Para cargar combobox
    public function readEmpleado()
    {
        $sql = 'SELECT id_empleado, nombre FROM empleados';
        return Database::getRows($sql);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO fichas (id_estudiante, descripcion_ficha, fecha_ficha, id_empleado)
        VALUES (?, ?, ?, ?)';
        $params = array($this->id_estudiante, $this->descripcion_ficha, $this->fecha_ficha, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE fichas SET id_estudiante = ?, descripcion_ficha = ?, fecha_ficha = ?, id_empleado = ? WHERE id_ficha = ? ';
        $params = array($this->id_estudiante, $this->descripcion_ficha, $this->fecha_ficha, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    //Metodo para eliminar un dato de la tabla por medio de un id específico

    public function deleteRow()
    {
        $sql = 'DELETE FROM fichas
                WHERE id_ficha = ?';
        $params = array($this->id_ficha);
        return Database::executeRow($sql, $params);
    }

}