<?php
require_once('../helpers/database.php');

class TrimestresQueries{

    //Método par insertar datos a la tabla de grados
    public function createRow()
    {
        $sql = 'INSERT INTO anios(anio)
        VALUES (?)';
    $params = array($this->anio);
    return Database::executeRow($sql, $params); 
    }

    //Método para leer todos los registros de la tabla, ordenados por el nombre del grado
    public function readAll()
    {
        $sql = 'SELECT id_trimestre, anio, trimestre, estado 
                FROM trimestres
                INNER JOIN anios USING(id_anio)
                ORDER BY anio DESC';
        return Database::getRows($sql);
    }

        //Método para leer todos los registros de la tabla, ordenados por el nombre del grado
        public function readOne()
        {
            $sql = 'SELECT id_anio, anio
                    FROM anios
                    WHERE id_anio = ?';
            $params = array($this->id_anio);
            return Database::executeRow($sql, $params); 
        }

          //Metodo para eliminar un dato de la tabla por medio del id
    public function deleteRow()
    {
        $sql = 'DELETE FROM anios
                WHERE id_anio = ?';
        $params = array($this->id_anio);
        return Database::executeRow($sql, $params);
    }
    // Método para actualizar el trimestre
    public function updateRow()
    {
        $sql = 'UPDATE trimestres set estado = 1 where id_trimestre = ?';
    $params = array($this->id_trimestre);
    return Database::executeRow($sql, $params); 
    }
    // método para actualizar el estado de los trimestres de la tabla
    public function updateTabla()
    {
        $sql = 'UPDATE trimestres set estado = 0 where id_trimestre != ?';
    $params = array($this->id_trimestre);
    return Database::executeRow($sql, $params); 
    }
}
?>