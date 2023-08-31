<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class PermisosQueries
{
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

}
?>