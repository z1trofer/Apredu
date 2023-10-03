<?php
require_once('../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad CLIENTE.
*/
class AsignaturasQueries
{

    //Método para insertar datos a la tabla de clientes por medio de una query 
    public function createRow()
    {
        $sql = 'INSERT INTO asignaturas(asignatura)
            VALUES (?)';
        $params = array($this->asignatura);
        return Database::executeRow($sql, $params);
    }

    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function readAll()
    {
        $sql = 'SELECT id_asignatura, asignatura
        FROM asignaturas ORDER BY id_asignatura
        ';
        return Database::getRows($sql);
    }

    //Método para consultar una columna específica de la tabla por medio de su id
    public function readOne()
    {
        $sql = 'SELECT id_asignatura, asignatura  FROM asignaturas
                WHERE id_asignatura = ?';
        $params = array($this->id_asignatura);
        return Database::getRow($sql, $params);
    }

        //Método para realizar actualización de datos en la tabla por medio de una query parametrizada
    public function updateRow()
    {
        $sql = 'UPDATE asignaturas
                SET asignatura= ? WHERE id_asignatura = ?';
        $params = array($this->asignatura, $this->id_asignatura);
        return Database::executeRow($sql, $params);
    }

  //Metodo para eliminar un dato de la tabla por medio de un id específico

    public function deleteRow()
    {
        $sql = 'DELETE FROM asignaturas
                WHERE id_asignatura = ?';
        $params = array($this->id_asignatura);
        return Database::executeRow($sql, $params);
    }

    public function MateriasDocentes()
    {
        $sql = 'SELECT nombre_empleado, COUNT(*) AS cantidad_materias_asignadas
        FROM detalle_asignaturas_empleados INNER JOIN empleados USING(id_empleado)
        GROUP BY nombre_empleado
        ORDER BY cantidad_materias_asignadas DESC
        LIMIT 5;
        ';
        return Database::getRows($sql);
    }


}
?>