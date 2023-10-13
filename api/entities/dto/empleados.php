<?php
require_once('../helpers/validator.php');
require_once('../entities/dao/empleados_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad Empleado.
*/
class Empleados extends EmpleadosQueries
{
    // Declaración de atributos (propiedades).
    protected $id_empleado = null;
    protected $nombre_empleado = null;
    protected $apellido_empleado = null;
    protected $dui = null;
    protected $correo_empleado = null;
    protected $direccion = null;
    protected $fecha_nacimiento = null;
    protected $id_cargo = null;
    protected $usuario_empleado = null;
    protected $clave = null;
    protected $estado = null;
    protected $id_grado = null;
    protected $id_asignatura = null;

    protected $telefono = null;



    /*
    *   Métodos para validar y asignar valores de los atributos. 13 campos
    */
    public function setIdEmpleado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombreEmpleado($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 30)) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }
        /*if ($value) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }*/
    }

    public function setApellidoEmpleado($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 30)) {
            $this->apellido_empleado = $value;
            return true;
        } else {
            return false;
        }
        /*if ($value) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }*/
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

    public function setCorreoEmpleado($value)
    {
        if (Validator::validateEmail($value, 1, 100)) {
            $this->correo_empleado = $value;
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

    public function setDireccion($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->direccion = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setFechaNacimiento($value)
    {
        if (Validator::validateDate18($value)) {
            $this->fecha_nacimiento = $value;
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

    public function setUsuarioEmpleado($value)
    {
        /*if ($value) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }*/
        if (Validator::validateString($value, 1, 50)) {
            $this->usuario_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave($value)
    {
        if (Validator::validatePassword($value)) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setestado($value)
    {
        if (Validator::validateBoolean($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function setIdGrado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_grado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdAsignatura($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_asignatura = $value;
            return true;
        } else {
            return false;
        }
    }
   
    
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getid_empleado()
    {
        return $this->id_empleado;
    }

    public function getnombre_empleado()
    {
        return $this->nombre_empleado;
    }

    public function getapellido_empleado()
    {
        return $this->apellido_empleado;
    }

    public function getdui()
    {
        return $this->dui;
    }

    public function getcorreo_empleado()
    {
        return $this->correo_empleado;
    }

    public function getdireccion()
    {
        return $this->direccion;
    }
    public function getfecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }
    public function getid_cargo()
    {
        return $this->id_cargo;
    }
    public function getusuario_empleado()
    {
        return $this->usuario_empleado;
    }
    public function getclave()
    {
        return $this->clave;
    }
    public function getEstado()
    {
        return $this->estado;
    }

    public function getid_grado()
    {
        return $this->id_grado;
    }

    public function getid_asignatura()
    {
        return $this->id_asignatura;
    }
    
    
}
?>