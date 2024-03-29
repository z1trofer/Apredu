<?php
require_once('../helpers/validator.php');
require_once('../entities/dao/usuarios_queries.php');
/*
 *	Clase para manejar la transferencia de datos de la entidad USUARIO.
 */
class Usuarios extends UsuariosQueries
{
    // Declaración de atributos (propiedades).
    public $id = null;
    public $usuario = null;
    public $clave = null;
    public $empleado = null;
    public $estado = null;
    public $nombre_empleado = null;
    public $apellido_empleado = null;
    public $correo_empleado = null;
    public $usuario_empleado = null;
    public $tiempo_inicio = null;
    public $dias_clave = null;
    public $dias_diferencia = null;
    public $tiempo_restante = null;
    public $codigo_recuperacion = null;

    public $telefono = null;


    //atributos xtra
    public $cargo = null;
    public $id_cargo = null;
    public $fecha_nacimiento = null;
    public $dui = null;
    public $direccion = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */

    public function setTiempoRest($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->tiempo_restante = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCodigoVerificar($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->codigo_recuperacion = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setUser($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave($value)
    {
        //validación de contraseña y conversión a hash
        if (Validator::validatePassword($value)) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setClaveLog($value)
    {
        //conversión a hash sin validar (solo para logIn)
        if ($value) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }
    public function setEmpleado($value)
    {
        $this->empleado = $value;
        return true;
    }

    public function setUsuarioEmpleado($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->usuario_empleado = $value;
            return true;
        } else {
            return false;
        }
    }



    public function setEstado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombreEmpleado($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 150)) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellidoEmpleado($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 150)) {
            $this->apellido_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreoEmpleado($value)
    {
        if (Validator::validateEmail($value, 1, 150)) {
            $this->correo_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    //tipo empleado
    public function setTipo_empleado($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->cargo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdCargo($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cargo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefono($value)
    {
        if (Validator::validatePhone($value)) {
            $this->telefono = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setFechaNacimiento($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_nacimiento = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDui($value)
    {
        if (Validator::validateDUI($value, 1, 10)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setDireccion($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->direccion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTiempoInicio($value)
    {
        if ($value) {
            $this->tiempo_inicio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDias_diferencia($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->dias_diferencia = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Métodos para obtener valores de los atributos.
     */
    public function getTiempoInicio()
    {
        return $this->tiempo_inicio;
    }

    public function getTiempoRest()
    {
        return $this->tiempo_restante;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->usuario;
    }

    public function getClave()
    {
        return $this->clave;
    }


    public function getEmpleado()
    {
        return $this->empleado;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getTipo_empleado()
    {
        return $this->cargo;
    }

    public function getId_cargo()
    {
        return $this->id_cargo;
    }

    public function getFecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }

    public function getCorreo_empleado()
    {
        return $this->correo_empleado;
    }


    public function getDui()
    {
        return $this->dui;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getDias_diferencia()
    {
        return $this->dias_diferencia;
    }

     public function getCodigoVerificar()
    {
        return $this->codigo_recuperacion;
    }
}
?>