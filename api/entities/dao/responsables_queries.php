<?php
require_once('../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad CLIENTE.
*/
class ResponsablesVistaQueries
{

    //Método para insertar datos a la tabla de clientes por medio de una query 
    public function createRow()
    {
        $sql = 'INSERT INTO responsables(nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono)
        VALUES (?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_responsable, $this->apellido_responsable, $this->dui, $this->correo, $this->lugar_trabajo, $this->telefono);
        return Database::executeRow($sql, $params);
    }

    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function readAll()
    {
        $sql = 'SELECT id_responsable ,nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono
        FROM responsables ORDER BY id_responsable
        ';
        return Database::getRows($sql);
    }

    //funcion para buscar responsables
    public function search($param, $idEs)
    {
        $sql = 'SELECT DISTINCT id_responsable ,nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono
        FROM responsables LEFT JOIN estudiantes USING(id_responsable) 
        WHERE (nombre_responsable LIKE ? or apellido_responsable LIKE ?)';
        if($idEs != 'todos'){
            $sql = $sql.' and estudiantes.id_estudiante = '.$idEs;
        };
        $sql = $sql.' ORDER BY id_responsable';
        $params = array("%$param%", "%$param%");
        return Database::getRows($sql, $params);
    }

    //Método para consultar una columna específica de la tabla por medio de su id
    public function readOne()
    {
        $sql = "SELECT id_responsable ,nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono
        FROM responsables WHERE id_responsable = ?";
        $params = array($this->id_responsable);
        return Database::getRow($sql, $params);
    }

    //actualizar el id del responsable en estudiantes
    public function updateEstId(){
        $sql = "UPDATE estudiantes SET id_responsable = ? where id_estudiante = ?";
        $params = array($this->id_responsable, $this->id_alumno);
        return Database::executeRow($sql, $params);
    }

    //Método para realizar actualización de datos en la tabla por medio de una query parametrizada
    public function updateRow()
    {
        $sql = 'UPDATE responsables
                SET nombre_responsable =?, apellido_responsable =?, dui =?, correo_responsable =?, lugar_de_trabajo =?, telefono =?
                WHERE id_responsable = ?';
        $params = array($this->nombre_responsable, $this->apellido_responsable, $this->dui, $this->correo, $this->lugar_trabajo, $this->telefono, $this->id_responsable);
        return Database::executeRow($sql, $params);
    }

    //Metodo para eliminar un dato de la tabla por medio de un id específico
    public function deleteRow()
    {
        $sql = 'DELETE FROM responsables
                WHERE id_responsable = ?';
        $params = array($this->id_responsable);
        return Database::executeRow($sql, $params);
    }

        //Metodo para remover un responsable de un estudiante
        public function removeRes()
        {
            $sql = 'UPDATE estudiantes SET id_responsable = null
                    WHERE id_responsable = ?';
            $params = array($this->id_responsable);
            return Database::executeRow($sql, $params);
        }


    public function SearchEstudiantes($param)
    {
        $sql = "SELECT id_estudiante, CONCAT(nombre_estudiante, ' ', apellido_estudiante) FROM estudiantes
            WHERE nombre_estudiante like ? OR apellido_estudiante like ?";
        $params = array("%$param%", "%$param%");
        return Database::getRows($sql, $params);
    }

    public function obtenerResponsableIDdui()
    {
        $sql = "SELECT id_responsable FROM responsables where dui = ?";
        $params = array($this->dui);
        $data =  Database::getRow($sql, $params);
        return $data['id_responsable'];
    }

    //reporte estudiantes (responsable segun id de estudiante)
    public function reportEstudiantesRes()
    {
        $sql = 'SELECT id_responsable ,nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco
        FROM responsables INNER JOIN responsables_estudiantes USING (id_responsable)
        INNER JOIN estudiantes USING (id_estudiante)
        WHERE estudiantes.id_estudiante = ?';
        $params = array($this->id_alumno);
        return Database::getRows($sql, $params);
    }
}
