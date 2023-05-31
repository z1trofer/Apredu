<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class UsuariosQueries
{
    public function LogIn($clave)
    {

        $sql = "SELECT usuarios_empleados.id_usuario_empleado, usuarios_empleados.usuario_empleado, clave, usuarios_empleados.id_empleado, cargos_empleados.id_cargo, cargos_empleados.cargo, usuarios_empleados.estado 
        from usuarios_empleados LEFT JOIN empleados USING (id_empleado)
        INNER JOIN cargos_empleados USING(id_cargo)
        WHERE usuario_empleado = ?";
        $params = array($this->usuario);
        $data = Database::getRow($sql, $params);
        if ($data == null) {
            return false;
        } /*elseif ($data['estado'] == 'false') {
            return 'zzz';
        } */elseif (password_verify($clave, $data['clave'])) {
            $this->id = $data['id_usuario_empleado'];
            $this->usuario = $data['usuario_empleado'];
            $this->cargo =  $data['cargo'];
            $this->id_cargo = $data['id_cargo'];
            return $data;
        } else {
            return false;
        }
    }
}
