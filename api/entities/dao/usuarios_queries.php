<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class UsuariosQueries
{
    public function LogIn(){
        
        $sql = "SELECT usuarios_empleados.id_usuario_empleado, usuarios_empleados.usuario_empleado, usuarios_empleados.id_empleado, cargos_empleados.id_cargo, usuarios_empleados.estado 
        from usuarios_empleados INNER JOIN empleados USING (id_empleado)
        INNER JOIN cargos_empleados USING(id_cargo)
        WHERE usuario = ? and clave = ?";
        $params = array($this->usuario, $this->clave);
        $data = Database::getRow($sql, $params);
        if ($data == false) {
            return 'false';
        } elseif ($data['estado'] == false) {
            return 'zzz';
        } else {
            $this->clave =  $data['clave'];
            $this->usuario = $data['usuario'];
            $this->tipo_empleado =  $data['id_cargo'];
            return true;
        }
    }
}
