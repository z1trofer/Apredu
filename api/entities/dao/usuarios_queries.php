<?php
require_once('../../helpers/database.php');
require_once('../../helpers/props.php');

/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class UsuariosQueries
{
    //funcion para verificar que todos los atributos solicitados en el parametro $access esten concedidos al cargo especificado
    /*public function getPermissions($access)
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
    }*/

    //funcion de login para permitir al usuario entrar al sistema
    public function LogIn($clave)
    {
        $sql = "SELECT empleados.id_empleado, empleados.usuario_empleado, clave, cargos_empleados.id_cargo, 
        cargos_empleados.cargo, CONCAT(empleados.nombre_empleado, ' ', empleados.apellido_empleado) as nombre,
        empleados.estado, empleados.intentos, DATEDIFF(CURRENT_DATE, fecha_clave) as dias, empleados.timer_intento, correo_empleado from empleados INNER JOIN cargos_empleados USING(id_cargo)
        WHERE usuario_empleado = ?";
        $params = array($this->usuario);
        $data = Database::getRow($sql, $params);
        if ($data == null) {
            return false;
        } elseif ($data['estado'] == false) {
            return 'zzz';
        } else {
            $timer = null;
            if (Validator::validateAttemptsCd($data['timer_intento']) != true) {
                $timer = false;
                $this->tiempo_restante = Validator::validateAttemptsCd($data['timer_intento']);
            } else {
                $this->usuario = $data['usuario_empleado'];
                $this->subirTiempoInicio(null);
                $timer = true;
            }
            if ($data['estado'] == false) {
                return 'zzz';
            } elseif (password_verify($clave, $data['clave'])) {
                $this->id = $data['id_empleado'];
                $this->usuario = $data['usuario_empleado'];
                $this->cargo = $data['cargo'];
                $this->id_cargo = $data['id_cargo'];
                $this->empleado = $data['nombre'];
                $this->dias_clave = $data['dias'];
                $this->correo_empleado = $data['correo_empleado'];
                return $data;
            } /*elseif ($data['intentos'] == 5 || $data['intentos'] == 10 || $data['intentos'] == 15 || $data['intentos'] == 20) {
                return 'time';
            } */elseif ($data['intentos'] > 3) {
                return 'bloquear';
            } elseif ($timer == false) {
                return 'timer';
            } else {
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
            $this->correo_empleado = $data['correo_empleado'];
            $_SESSION['id_empleado_clave'] = $data['id_empleado'];
            return true;
        } elseif($data['estado'] == false) {
            return false;
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
        $sql = 'INSERT INTO empleados (nombre_empleado, apellido_empleado, dui, fecha_nacimiento, id_cargo, usuario_empleado, direccion, clave, correo_empleado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui, $this->fecha_nacimiento, $this->id_cargo, $this->usuario, $this->direccion, $this->clave, $this->correo_empleado);
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

    //Método para comprobar el usuario

    public function checkRecovery()

    {

        $sql = 'SELECT id_empleado, usuario_empleado FROM empleados WHERE correo_empleado = ? ';
        $params = array($this->correo_empleado);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['id_empleado'];
            $this->usuario_empleado = $data['usuario_empleado'];
            //Create an instance; passing `true` enables exceptions
            $this->codigo_recuperacion = rand(100000, 999999);
            $mensaje = 'Mensaje de verificación';
            $mailSubject = 'Código de verificación de contraseña';
            $mailAltBody = '¡Te saludamos la asistencia del colegio Aprendo COntigo para enviarte el código de verificación, por favor ingresarlo en el formulario!';
            $mailBody = '<!DOCTYPE html>

                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>' . $mailSubject . '</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

                        }
                        h1 {
                            color: #333;
                        }
                        p {
                            color: #666;
                        }
                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            background-color: #007BFF;
                            color: #fff;
                            text-decoration: none;
                            border-radius: 3px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>Código de verificación</h1>
                        <p>' . $mailAltBody . '</p>
                        <p>' . $mensaje . '</p>
                        <p>' . $random_string_number . '</p>

                    </div>

                </body>

                </html>';

            return Props::sendMail($this->correo_empleado, $mailSubject, $mailBody);
        } else {

            return false;
        }
    }
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
