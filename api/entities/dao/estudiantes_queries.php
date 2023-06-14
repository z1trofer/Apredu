<?php
require_once('../../helpers/database.php');

class EstudiantesQueries
{
    //Método para insertar datos a la tabla de clientes por medio de una query 
    public function CreateEstudiante()
    {
        $sql = 'INSERT INTO estudiantes(
            id_estudiante, nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, id_grado, usuario_estudiante, clave, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_estudiante, $this->apellido_estudiante, $this->nacimiento, $this->direccion_estudiante, $this->nie, $this->id_grado, $this->usuario_estudiante, $this->clave, $_SESSION['id_empleado']);
    }

    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function ReadEstudiantes()
    {
        $sql ='SELECT id_estudiante, nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, id_grado, usuario_estudiante, clave, estado
            FROM estudiantes
            order by nombre_estudiante';
        return Database::getRows($sql);
    }

    //Método para consultar una columna específica de la tabla por medio de su id
    public function ReadOneEstudiante()
    {
        $sql ='SELECT id_estudiante, nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, id_grado, usuario_estudiante, clave, estado
            FROM estudiantes
            WHERE id_estudiante =?';       
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}
