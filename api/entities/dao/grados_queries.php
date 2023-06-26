<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad Grado.
*/
class GradosQueries
{
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */


    //Método par insertar datos a la tabla de grados
    public function createRow()
    {
        $sql = 'INSERT INTO grados(grado)
                VALUES(?)';
        $params = array($this->grado);
        return Database::executeRow($sql, $params);
    }

    //Método para leer todos los registros de la tabla, ordenados por el nombre del grado
    public function readAll()
    {
        $sql = 'SELECT id_grado, grado 
                FROM grados
                ORDER BY id_grado';
        return Database::getRows($sql);
    }

    //Método para consultar datos de una columna específica por medio de un parametro del id
    public function readOne()
    {
        $sql = 'SELECT id_grado, grado
                FROM grados
                WHERE id_grado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //Método para realizar la actualización de la tabla por medio de una query parametrizada
    public function updateRow()
    {
        $sql = 'UPDATE grados
                SET grado = ?
                WHERE id_grado = ?';
        $params = array($this->grado, $this->id);
        return Database::executeRow($sql, $params);
    }

  //Metodo para eliminar un dato de la tabla por medio del id
    public function deleteRow()
    {
        $sql = 'DELETE FROM grados
                WHERE id_grado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Obtener asignaturas de de un grado
    public function readDetalle()
    {
        $sql = 'SELECT id_detalle_asignatura_empleado, asignaturas.asignatura from detalle_asignaturas_empleados 
        INNER JOIN asignaturas USING (id_asignatura)
        where id_grado = ? ORDER BY asignatura ASC';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    public function InsertarDetalle($asignatura)
    {
        $sql = 'INSERT INTO detalle_asignaturas_empleados (id_asignatura, id_grado)
                VALUES(?, ?)';
        $params = array( $asignatura, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteDetalle()
    {
        $sql = 'DELETE FROM detalle_asignaturas_empleados
                WHERE id_detalle_asignatura_empleado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readAsignaturas()
    {
        $sql = "SELECT id_asignatura, asignatura
            FROM asignaturas"; 
        return Database::getRows($sql);
    }
}
