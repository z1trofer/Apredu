<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/empleados_queries.php');
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



    /*
    *   Métodos para validar y asignar valores de los atributos. 13 campos
    */
    public function setid_empleado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setnombre_empleado($value)
    {
        if (Validator::validateString($value, 1, 50)) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setapellido_empleado($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->apellido_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setdui($value)
    {
        if (Validator::validateDUI($value, 1, 10)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setcorreo_empleado($value)
    {
        if (Validator::validateEmail($value, 1, 150)) {
            $this->correo_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setdireccion($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->direccion = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setfecha_nacimiento($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_nacimiento = $value;
            return true;
        } else {
            return false;
        }
    }
    

    public function setid_cargo($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cargo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setusuario_empleado($value)
    {
        if (Validator::validateString($value, 1, 50)) {
            $this->usuario_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setclave($value)
    {
        if (Validator::validatePassword($value, 1, 100)) {
            $this->clave = $value;
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
    
    public function setid_grado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_grado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setid_asignatura($value)
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
    public function getestado()
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
