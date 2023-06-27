<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class UsuariosQueries
{
    public function LogIn($clave)
    {
        $sql = "SELECT empleados.id_empleado, empleados.usuario_empleado, clave, cargos_empleados.id_cargo, 
        cargos_empleados.cargo, CONCAT(empleados.nombre_empleado, ' ', empleados.apellido_empleado) as nombre,
        empleados.estado from empleados INNER JOIN cargos_empleados USING(id_cargo)
        WHERE usuario_empleado = ?";
        $params = array($this->usuario);
        $data = Database::getRow($sql, $params);
        if ($data == null) {
            return false;
        } elseif ($data['estado'] == false) {
            return 'zzz';
        } elseif (password_verify($clave, $data['clave'])) {
            $this->id = $data['id_empleado'];
            $this->usuario = $data['usuario_empleado'];
            $this->cargo =  $data['cargo'];
            $this->id_cargo = $data['id_cargo'];
            $this->empleado = $data['nombre'];
            return $data;
        } else {
            return false;
        }
    }
}
?>