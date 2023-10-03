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
        $sql = 'INSERT INTO responsables(nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco)
        VALUES ( ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_responsable, $this->apellido_responsable, $this->dui, $this->correo, $this->lugar_trabajo, $this->telefono_trabajo, $this->parentesco);
        return Database::executeRow($sql, $params);
    }

    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function readAll()
    {
        $sql = 'SELECT id_responsable ,nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco
        FROM responsables ORDER BY id_responsable
        ';
        return Database::getRows($sql);
    }

    //Método para consultar una columna específica de la tabla por medio de su id
    public function readOne()
    {
        $sql = "SELECT id_responsable ,nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco, CONCAT(estudiantes.nombre_estudiante, ' ', estudiantes.apellido_estudiante) as estudiante
        FROM responsables INNER JOIN responsables_estudiantes USING (id_responsable)
        INNER JOIN estudiantes USING (id_estudiante)
        WHERE id_responsable = ?";
        $params = array($this->id_responsable);
        return Database::getRow($sql, $params);
    }

    //Método para realizar actualización de datos en la tabla por medio de una query parametrizada
    public function updateRow()
    {
        $sql = 'UPDATE responsables
                SET nombre_responsable =?, apellido_responsable =?, dui =?, correo_responsable =?, lugar_de_trabajo =?, telefono_trabajo =?, parentesco=? 
                WHERE id_responsable = ?';
        $params = array($this->nombre_responsable, $this->apellido_responsable, $this->dui, $this->correo, $this->lugar_trabajo, $this->telefono_trabajo, $this->parentesco,  $this->id_responsable);
        return Database::executeRow($sql, $params);
    }

    //Metodo para eliminar un dato de la tabla por medio de un id específico

    public function deleteRow()
    {
        $sql = 'DELETE FROM responsable
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

    public function ObtenerResponsableIDdui()
    {
        $sql = "SELECT id_responsable FROM responsables where dui = ?";
        $params = array($this->dui);
        $data =  Database::getRow($sql, $params);
        return $data['id_responsable'];
    }

    public function AgregarResponsableDetalle()
    {
        $sql = 'INSERT INTO responsables_estudiantes(id_responsable, id_estudiante)
        VALUES (?, ?)';
        $params = array($this->id_responsable, $this->id_alumno);
        return Database::executeRow($sql, $params);
    }

    public function ActualizarResponsableDetalle()
    {
        $sql = 'UPDATE responsables_estudiantes set id_estudiante = ? where id_responsable = ?';
        $params = array($this->id_alumno, $this->id_responsable);
        return Database::executeRow($sql, $params);
    }
}
?>