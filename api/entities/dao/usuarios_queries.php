<?php
require_once('../helpers/database.php');
require_once('../helpers/props.php');

/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class UsuariosQueries
{
    //funcion de login para permitir al usuario entrar al sistema
    public function logIn($clave)
    {
        $sql = "SELECT empleados.id_empleado, empleados.usuario_empleado, clave, cargos_empleados.id_cargo, 
        cargos_empleados.cargo, CONCAT(empleados.nombre_empleado, ' ', empleados.apellido_empleado) as nombre,
        empleados.estado, empleados.intentos, DATEDIFF(CURRENT_DATE, fecha_clave) as dias, empleados.timer_intento, correo_empleado from empleados INNER JOIN cargos_empleados USING(id_cargo)
        WHERE usuario_empleado = BINARY ?";
        $params = array($this->usuario);
        $data = Database::getRow($sql, $params);
        //se valida el resultado
        if ($data == null) {
            //error en base de datos
            return false;
        } elseif ($data['estado'] == false) {
            //el usuario esta bloqueado
            return 'zzz';
        } else {
            $timer = null;
            //se verifica si el usuario tiene contador de tiempo
            if (Validator::validateAttemptsCd($data['timer_intento']) != true) {
                //el usuario tiene contador de tiempo
                $timer = false;
                $this->tiempo_restante = Validator::validateAttemptsCd($data['timer_intento']);
            } else {
                //el usuario no tiene contador
                $this->usuario = $data['usuario_empleado'];
                $this->subirTiempoInicio(null);
                $timer = true;
            }
            if ($timer == false) {
                //el usuario tiene contador de tiempo
                return 'timer';
            } elseif (password_verify($clave, $data['clave'])) {
                //las contraseñas coinciden
                $this->id = $data['id_empleado'];
                $this->usuario = $data['usuario_empleado'];
                $this->cargo = $data['cargo'];
                $this->id_cargo = $data['id_cargo'];
                $this->empleado = $data['nombre'];
                $this->dias_clave = $data['dias'];
                $this->correo_empleado = $data['correo_empleado'];
                return $data;
            } elseif ($data['intentos'] == 6 || $data['intentos'] == 12 || $data['intentos'] == 18 || $data['intentos'] == 24) {
                //las contraseñas no coinciden, se validan los intentos de sesión para ver si el usuario deberia tener un cotnador
                return 'time';
            } elseif ($data['intentos'] > 30) {
                //las contraseñas no coinciden, se valida los intentos para ver si el usuario debe ser bloqueado
                return 'bloquear';
            } else {
                //el usuario fallo al incicar sesión
                return 'fail';
            }
        }
    }

    //Agregar un intento fallido de inicio al usuario
    public function agregarIntento()
    {
        $sql = 'UPDATE empleados set intentos = intentos+1 where usuario_empleado = ?';
        $params = array($this->usuario);
        return Database::executeRow($sql, $params);
    }

    //Reiniciar el contador de intentos a 0
    public function resetIntentos()
    {
        $sql = 'UPDATE empleados set intentos = 0 where usuario_empleado = ?';
        $params = array($this->usuario);
        return Database::executeRow($sql, $params);
    }

    //cambiar el contador de tiempo para incicar sesion nuevamente
    public function subirTiempoInicio($timer)
    {
        $sql = 'UPDATE empleados set timer_intento = ? where usuario_empleado = ?';
        $params = array($timer, $this->usuario);
        return Database::executeRow($sql, $params);
    }

    //obtener los atributos del usuario para la vista del sitio
    public function obtenerAtributosVista($access)
    {
        $sql = 'SELECT ' . implode(",", $access) . ' from cargos_empleados INNER JOIN empleados USING(id_cargo) where empleados.id_empleado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //bloquear un usuario
    public function blockUser()
    {
        $sql = 'UPDATE empleados set estado = 0 where usuario_empleado = ?';
        $params = array($this->usuario);
        return Database::executeRow($sql, $params);
    }

    //verificar que la contraseña coincida con la de un usuario
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

    //Obtener el correo de un empleado que no ha iniciado sesion
    public function getCorreo()
    {
        $sql = 'SELECT id_empleado, correo_empleado, estado from empleados where usuario_empleado = ?';
        $params = array($this->usuario);
        $data = Database::getRow($sql, $params);
        if ($data != false) {
            if ($data['estado'] == 0) {
                return false;
            } else {
                $this->correo_empleado = $data['correo_empleado'];
                $_SESSION['id_empleado_clave'] = $data['id_empleado'];
                return true;
            }
        } else {
            return false;
        }
    }


    //cambiar la contraseña de un usuario
    public function changePassword()
    {
        $sql = 'UPDATE empleados
                SET clave = ?, fecha_clave = current_timestamp()	
                WHERE id_empleado = ?';
        $params = array($this->clave, $this->id);
        return Database::executeRow($sql, $params);
    }

    //cambiar la contraseña que ya caducó
    public function changePasswordCaducada()
    {
        $sql = 'UPDATE empleados
                SET clave = ?
                WHERE usuario_empleado = ?';
        $params = array($this->clave, $this->usuario_empleado);
        return Database::executeRow($sql, $params);
    }

    //obtener los datos personales de un usuario
    public function readProfile()
    {
        $sql = 'SELECT id_empleado, nombre_empleado, apellido_empleado, correo_empleado, usuario_empleado 
                FROM empleados 
                WHERE id_empleado = ?';
        $params = array($_SESSION['id_empleado']);
        return Database::getRow($sql, $params);
    }

    //cambiar los datos personales de un usuario
    public function editProfile()
    {
        $sql = 'UPDATE empleados
        SET nombre_empleado = ?, apellido_empleado = ?, correo_empleado = ?, usuario_empleado = ?
        WHERE id_empleado = ?';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->correo_empleado, $this->usuario_empleado, $_SESSION['id_empleado']);
        return Database::executeRow($sql, $params);
    }

    //Funcion para crear un usuario en el primer uso
    public function createRow()
    {
        $sql = 'INSERT INTO empleados (nombre_empleado, apellido_empleado, dui, fecha_nacimiento, id_cargo, usuario_empleado, direccion, clave, correo_empleado, telefono)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario, $this->direccion, $this->clave, $this->correo_empleado, $this->telefono);
        return Database::executeRow($sql, $params);
    }

    //obtener los cargos
    public function readCargos()
    {
        $sql = 'SELECT id_cargo, cargo 
        FROM cargos_empleados 
        ORDER BY cargo ASC 
        LIMIT 1';
        return Database::getRows($sql);
    }

    //Función para evitar una vulnerabilidad en el primer usuario leyendo si ya existe un empleado
    public function readAll()
    {
        $sql = 'SELECT empleados.id_empleado, empleados.nombre_empleado, empleados.apellido_empleado, empleados.dui, empleados.fecha_nacimiento, cargos_empleados.cargo, empleados.usuario_empleado, empleados.correo_empleado
        FROM empleados 
        INNER JOIN cargos_empleados USING (id_cargo)';
        return Database::getRows($sql);
    }

    //obtener la cantidad de dia desde el ultimo cambio de contraseña
    public function readDiasClave()
    {
        $sql = 'SELECT DATEDIFF(CURRENT_DATE, fecha_clave) as dias FROM empleados WHERE id_empleado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //validar empleado, autenticación de 2 pasos
    public function checkAD($id)
    {
        $sql = 'SELECT id_empleado, usuario_empleado, cargos_empleados.cargo, cargos_empleados.id_cargo FROM empleados
        INNER JOIN cargos_empleados USING (id_cargo)
        WHERE id_empleado = ?';
        $params = array($id);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['id_empleado'];
            $this->usuario_empleado = $data['usuario_empleado'];
            $this->cargo = $data['cargo'];
            $this->id_cargo = $data['id_cargo'];
            return true;
        } else {
            return false;
        }
    }
}
