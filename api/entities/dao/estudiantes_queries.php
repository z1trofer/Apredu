<?php
require_once('../../helpers/database.php');

class EstudiantesQueries
{
    //Método para insertar datos a la tabla de clientes por medio de una query 
    public function CreateEstudiante()
    {
        $sql = 'INSERT INTO estudiantes(
            nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, id_grado, usuario_estudiante, clave, estado)
            VALUES (?, ?, ?, ?, ?, (select id_grado from grados where grado = ?), ?, ?)';
        $params = array($this->nombre_estudiante, $this->apellido_estudiante, $this->nacimiento, $this->direccion_estudiante, $this->nie, $this->id_grado, $this->usuario_estudiante, $this->clave);
    }

    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function readAll()
    {
        $sql ='SELECT id_estudiante, nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, grado, usuario_estudiante, clave, estado
                FROM estudiantes
                INNER JOIN grados USING(id_grado)
                order by id_grado ASC';
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

    public function checkUser($usuario_estudiante)
    {
        $sql = 'SELECT id_estudiante, estado FROM estudiantes WHERE usuario_estudiante = ?';
        $params = array($usuario_estudiante);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_estudiante = $data['id_estudiante'];
            $this->estado = $data['estado'];
            $this->usuario_estudiante = $usuario_estudiante;
            return true;
        } else {
            return false;
        }
    }

    //se valida la contraseña
    public function checkPassword($password)
    {
        $sql = 'SELECT clave FROM estudiantes WHERE id_estudiante = ?';
        $params = array($this->id_estudiante);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave'])) {
            return true;
        } else {
            return false;
        }
    }
}
