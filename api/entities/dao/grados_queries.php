<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad Grado.
*/
class GradoQueries
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
        $sql = 'SELECT id_grado, grado, 
                FROM grados
                ORDER BY grado';
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
}
