<?php
require_once('../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class FichasQueries
{
    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function readAllFichas()
    {
        $sql ='SELECT id_ficha, nombre_estudiante, apellido_estudiante, grado, descripcion_ficha, fecha_ficha, nombre_empleado
        FROM fichas  INNER JOIN estudiantes USING(id_estudiante) INNER JOIN empleados USING(id_empleado) INNER JOIN grados USING(id_grado)
        ORDER BY fecha_ficha';
        return Database::getRows($sql);
    }
    public function readAll()
    {
        $sql ='SELECT id_estudiante, nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, grado, usuario_estudiante, clave, estado
                FROM estudiantes
                INNER JOIN grados USING(id_grado)
                order by id_grado ASC';
        return Database::getRows($sql);
    }

    public function readOneestudiante()
    {
        $sql = 'SELECT id_estudiante, nombre_estudiante, apellido_estudiante, grado 
        FROM estudiantes
        INNER JOIN grados USING(id_grado)
        WHERE id_estudiante = ?';
        $params = array($this->id_estudiante);
        return Database::getRow($sql, $params);
    }

    public function readOneFichaXestudiante()
    {
        $sql = 'SELECT id_ficha, descripcion_ficha, fecha_ficha, nombre_empleado, nombre_estudiante
        FROM fichas 
        INNER JOIN estudiantes USING (id_estudiante)
        INNER JOIN grados USING (id_grado)
        INNER JOIN empleados USING (id_empleado)
        WHERE id_estudiante = ?';
        $params = array($this->id_estudiante);
        return Database::getRows($sql, $params);
    }
    /*funcion para leer datos*/
    public function readOne()
    {
        $sql = 'SELECT id_ficha,id_estudiante,descripcion_ficha, fecha_ficha, nombre_empleado
        FROM fichas 
        INNER JOIN empleados USING (id_empleado)
        WHERE id_ficha = ?';
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
        $sql = 'SELECT id_empleado, nombre_empleado FROM empleados';
        return Database::getRows($sql);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO fichas (id_estudiante, descripcion_ficha, id_empleado)
        VALUES (?, ?, ?)';
        $params = array($this->id_estudiante, $this->descripcion_ficha, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE fichas SET id_estudiante = ?, descripcion_ficha = ?, fecha_ficha = ?, id_empleado = ? WHERE id_ficha = ?';
        $params = array($this->id_estudiante, $this->descripcion_ficha,$this->fecha_ficha, $this->id_empleado,$this->id_ficha);
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

    public function readFichasPorSemana()
    {
        $sql ='SELECT nombre_estudiante,apellido_estudiante,descripcion_ficha, fecha_ficha
        FROM fichas INNER JOIN estudiantes USING(id_estudiante)
        WHERE YEARWEEK(fecha_ficha, 1) = YEARWEEK(CURDATE(), 1);
        ';
        return Database::getRows($sql);
    }

    public function FichasXestudiante()
    {
        $sql = 'SELECT descripcion_ficha,nombre_empleado, fecha_ficha
        FROM fichas INNER JOIN empleados USING(id_empleado)
        WHERE id_estudiante = ?';
        $params = array($this->id_estudiante);
        return Database::getRows($sql, $params);
    }

    public function EstudianteMasReportes($param)
    {
        $sql = "SELECT nombre_estudiante, COUNT(id_ficha) AS cantidad_ficha
        FROM fichas INNER JOIN estudiantes USING(id_estudiante)";
        if($param != "Todos"){
        $sql = $sql." WHERE estudiantes.id_grado = $param";
        }
        $sql = $sql." GROUP BY nombre_estudiante
        ORDER BY cantidad_ficha DESC
        LIMIT 5";
        return Database::getRows($sql);

    }
}
?>