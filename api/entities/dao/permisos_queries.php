<?php
require_once('../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class PermisosQueries
{
    //obtener permisos para cualquier proceso del sistema
    public function getPermissions($access)
    {
        $sql = 'SELECT ' . implode(",", $access). ' from cargos_empleados INNER JOIN empleados USING(id_cargo) where empleados.id_empleado = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        $autorized = true;
        foreach ($data as $permission) {
            if ($permission == 0) {
                $autorized = false;
            }
        }
        return $autorized;
    }

    //funcion para cambiar los permisos de un cargo
    public function changePermissions()
    {
        $sql = 'UPDATE cargos_empleados SET '.$this->atributo.' = ? where id_cargo = ?';
        $params = array($this->permiso, $this->id_cargo);
        return Database::executeRow($sql, $params);
    }

    //funcion para obtener los cargos con todos sus permisos
    public function viewPermissions($access)
    {
        $arreglo = array('id_cargo','cargo');
        foreach ($access as $data) {
            array_push($arreglo, $data[0]);
        }
        $sql = 'SELECT ' . implode(",", $arreglo). ' from cargos_empleados';
        return Database::getRowsColumns($sql);
    }

    //obtener el nombre de las columnas
    public function getHeaders()
    {
        $sql = "SELECT DISTINCT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'cargos_empleados' and COLUMN_NAME != 'cargo' and COLUMN_NAME != 'id_cargo' and COLUMN_NAME != 'create_estudiantes' and COLUMN_NAME != 'update_estudiantes'";
        return Database::getRowsColumns($sql);
    }

    //crear un nuevo cargo
    public function agregarCargo()
    {
        $sql = "INSERT INTO cargos_empleados (cargo) VALUES (?)";
        $params = array($this->cargo);
        return Database::executeRow($sql, $params);
    }
    
    //eliminar un cargo
    public function eliminarCargo()
    {
        $sql = "DELETE FROM cargos_empleados where id_cargo = ?";
        $params = array($this->id_cargo);
        return Database::executeRow($sql, $params);
    }
}
?>