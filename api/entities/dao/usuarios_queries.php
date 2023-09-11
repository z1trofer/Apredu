<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class UsuariosQueries
{
    public function getPermissions($access)
    {
        $sql = 'SELECT ' . implode(",", $access) . ' from cargos_empleados INNER JOIN empleados USING(id_cargo) where empleados.id_empleado = ?';
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

    public function LogIn($clave)
    {
        $sql = "SELECT empleados.id_empleado, empleados.usuario_empleado, clave, cargos_empleados.id_cargo, 
        cargos_empleados.cargo, CONCAT(empleados.nombre_empleado, ' ', empleados.apellido_empleado) as nombre,
        empleados.estado, empleados.intentos from empleados INNER JOIN cargos_empleados USING(id_cargo)
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
        } elseif($data['intentos'] == 5 || $data['intentos'] == 10 || $data['intentos'] == 15 || $data['intentos'] == 20) {
            return 'time';
        } elseif($data['intentos'] >= 25) {
            return 'bloquear';
        } else {
            return 'fail';
        }
    }

    public function agregarIntento()
    {
        $sql = 'UPDATE empleados set intentos = intentos+1 where usuario_empleado = ?';
        $params = array($this->usuario);
        return Database::executeRow($sql, $params);
    }

    public function resetIntentos()
    {
        $sql = 'UPDATE empleados set intentos = 0 where usuario_empleado = ?';
        $params = array($this->usuario);
        return Database::executeRow($sql, $params);
    }

    public function blockUser()
    {
        $sql = 'UPDATE empleados set estado = 0 where usuario_empleado = ?';
        $params = array($this->usuario);
        return Database::executeRow($sql, $params);
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave
                FROM empleados
                WHERE id_empleado = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE empleados
                SET clave = ?
                WHERE id_empleado = ?';
        $params = array($this->clave, $_SESSION['id_empleado']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_empleado, nombre_empleado, apellido_empleado, correo_empleado, usuario_empleado 
                FROM empleados 
                WHERE id_empleado = ?';
        $params = array($_SESSION['id_empleado']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE empleados
        SET nombre_empleado = ?, apellido_empleado = ?, correo_empleado = ?, usuario_empleado = ?
        WHERE id_empleado = ?';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->correo_empleado, $this->usuario_empleado, $_SESSION['id_empleado']);
        return Database::executeRow($sql, $params);
    }


    public function createRow()
    {
        $sql = 'INSERT INTO empleados (nombre_empleado, apellido_empleado, dui, fecha_nacimiento, id_cargo, usuario_empleado, direccion, clave, correo_empleado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario, $this->direccion, $this->clave, $this->correo_empleado);
        return Database::executeRow($sql, $params);
    }

    public function readCargos()
    {
        $sql = 'SELECT id_cargo, cargo 
        FROM cargos_empleados 
        ORDER BY cargo ASC 
        LIMIT 1';
        return Database::getRows($sql);
    }

    
    public function readAll()
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado
        FROM empleados 
        INNER JOIN cargos_empleados USING (id_cargo)';
        return Database::getRows($sql);
    }

}
