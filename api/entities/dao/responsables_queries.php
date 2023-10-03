<?php
require_once('../helpers/database.php');

class ResponsablesQueries
{
    //Método para insertar datos a la tabla de clientes por medio de una query 
    public function createResponsable()
    {
        $sql = 'INSERT INTO responsables(
            nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco)
            VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_responsable, $this->apellido_responsable, $this->dui, $this->correo, $this->lugar_trabajo, $this->telefono_trabajo, $this->parentesco);
        return Database::executeRow($sql, $params);
    }

    //Método para leer los registros de la tabla ordenandolos por sus apellidos por medio de una query general a la tabla
    public function readResponsables(){

        $sql ='SELECT 
        id_responsable, nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco
        FROM responsables
        ORDER BY nombre_responsable';
        return Database::getRows($sql);
    }

    //Método para consultar una columna específica de la tabla por medio de su id
    public function readOneResponsable(){

        $sql ='SELECT 
        id_responsable, nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco
        FROM responsables
        WHERE id_responsable = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}
?>