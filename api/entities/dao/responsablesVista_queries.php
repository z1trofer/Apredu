<?php
require_once('../../helpers/database.php');
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
        $sql = 'SELECT id_responsable ,nombre_responsable, apellido_responsable, dui, correo_responsable, lugar_de_trabajo, telefono_trabajo, parentesco  
        FROM responsables
        WHERE id_responsable = ?';
        $params = array($this->id_responsable);
        return Database::getRow($sql, $params);
    }

        //Método para realizar actualización de datos en la tabla por medio de una query parametrizada
    public function updateRow()
    {
        $sql = 'UPDATE responsables
                SET nombre_responsable =?, apellido_responsable =?, dui =?, correo_responsable =?, lugar_de_trabajo =?, telefono_trabajo =?, parentesco=? 
                WHERE id_responsable = ?';
        $params = array($this->nombre_responsable, $this->apellido_responsable, $this->dui, $this->correo_responsable, $this->lugar_de_trabajo, $this->telefono_trabajo, $this->parentesco,  $this->id_responsable);
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
}