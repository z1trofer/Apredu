<?php
require_once('../../helpers/database.php');

class EstudiantesQueries
{
    //Método para insertar datos a la tabla de clientes por medio de una query 
    public function CreateEstudiante()
    {
        $sql = 'INSERT INTO estudiantes(
            nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, id_grado, usuario_estudiante, clave, estado)
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_estudiante, $this->apellido_estudiante, $this->nacimiento, $this->direccion_estudiante, $this->nie, $this->id_grado, $this->usuario_estudiante, $this->clave, $this->estado);
        return Database::executeRow($sql, $params);
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
    public function ReadOne()
    {
        $sql ='SELECT id_estudiante, nombre_estudiante, apellido_estudiante, fecha_nacimiento, direccion, nie, id_grado, grado, usuario_estudiante, estado
            FROM estudiantes
            INNER JOIN grados USING(id_grado)
            WHERE id_estudiante = ?';
        $params = array($this->id_estudiante);
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
    //Metodo para leer los grados de la tabla "grados"
    public function readGrado()
    {
        $sql = 'SELECT id_grado, grado
                FROM grados';
            return Database::getRows($sql);
    }

    public function readConducta()
    {
        $sql = 'SELECT id_ficha, id_estudiante ,nombre_estudiante, descripcion_ficha, fecha_ficha, nombre_empleado, id_grado, grado
        FROM estudiantes 
        INNER JOIN empleados USING (id_empleado)
        INNER JOIN grados USING (id_grado)
        Where id_ficha = ?';
        $params = array($this->id_ficha);
        return Database::getRow($sql, $params);
    }


    //Metodo para actualizar un dato de la tabla por medio del id
    public function UpdateEstudiante()
    {
        $sql = 'UPDATE estudiantes
        SET nombre_estudiante=?, apellido_estudiante=?, fecha_nacimiento=?, direccion=?, nie=?, id_grado=?, usuario_estudiante=?, clave=?, estado=?
        WHERE id_estudiante=?';
        $params = array($this->nombre_estudiante, $this->apellido_estudiante, $this->nacimiento, $this->direccion_estudiante, $this->nie, $this->id_grado, $this->usuario_estudiante, $this->clave, $this->estado, $this->id_estudiante);
        return Database::executeRow($sql, $params);
    }

        //Metodo para eliminar un dato de la tabla por medio del id
        public function deleteEstudiante()
        {
            $sql = 'DELETE FROM estudiantes
                    WHERE id_estudiante = ?';
            $params = array($this->id_estudiante);
            return Database::executeRow($sql, $params);
        }

        //busqueda parametrizada por grados
        public function FiltrarEstudiante($filtros)
        {
            $sql = "SELECT estudiantes.id_estudiante, estudiantes.nombre_estudiante, estudiantes.apellido_estudiante, grados.grado
            FROM estudiantes 
            INNER JOIN grados USING(id_grado)
            WHERE id_grado = ".$filtros['grado']." 
            GROUP BY  estudiantes.apellido_estudiante, estudiantes.nombre_estudiante, grados.grado
            ORDER BY grados.grado ASC";
            return Database::getRows($sql);
        }
    

    public function createFicha()
    {
        $sql = 'INSERT INTO fichas (id_estudiante, descripcion_ficha, id_empleado)
        VALUES (?, ?, ?)';
        $params = array($this->id_estudiante, $this->descripcion_ficha, $this->id_empleado);
        return Database::executeRow($sql, $params);
    } 

    //consulta para el reporte de estudiantes por grados
    public function reporteEstudiantes(){
            $sql = "SELECT g.grado, e.nombre_estudiante, e.apellido_estudiante, e.nie, e.direccion, g.grado
            FROM grados g
            JOIN estudiantes e USING(id_grado)
            WHERE id_grado = ?
            ORDER BY g.grado, e.apellido_estudiante, e.nombre_estudiante";
        $params = array($this->id_grado);
        return Database::getRows($sql, $params);
    }
}
?>